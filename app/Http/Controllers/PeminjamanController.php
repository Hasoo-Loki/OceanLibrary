<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Book;
use App\Models\Denda;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PeminjamanController extends Controller
{
    // 🔹 PINJAM (User) - Show form pilih durasi
    public function pinjam($book_id)
    {
        $book = Book::findOrFail($book_id);

        if (!auth()->user()->is_member) {
            return back()->with('error', 'Kamu belum menjadi member');
        }

        if ($book->stock <= 0) {
            return back()->with('error', 'Stok buku ini sudah habis');
        }

        $dipakai = Peminjaman::where('book_id', $book_id)
            ->whereIn('status', ['pending', 'dipinjam'])
            ->exists();

        if ($dipakai) {
            return back()->with('error', 'Buku sedang diproses atau dipinjam');
        }

        $total = Peminjaman::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'dipinjam'])
            ->count();

        if ($total >= 3) {
            return back()->with('error', 'Maksimal 3 buku');
        }

        $sudah = Peminjaman::where('user_id', auth()->id())
            ->where('book_id', $book_id)
            ->whereIn('status', ['pending', 'dipinjam'])
            ->exists();

        if ($sudah) {
            return back()->with('error', 'Kamu sudah request buku ini');
        }

        // Return ke halaman dengan modal terbuka
        return back()->with([
            'showPinjamModal' => true,
            'pinjam_book_id' => $book_id
        ]);
    }

    // 🔹 SUBMIT PEMINJAMAN dengan durasi
    public function submitPinjam(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'durasi'  => 'required|in:1,3,7',
        ]);

        $book_id = $request->book_id;
        $durasi  = (int) $request->durasi;
        $book    = Book::findOrFail($book_id);

        if (!auth()->user()->is_member) {
            return back()->with('error', 'Kamu belum menjadi member');
        }

        if ($book->stock <= 0) {
            return back()->with('error', 'Stok buku ini sudah habis');
        }

        $dipakai = Peminjaman::where('book_id', $book_id)
            ->whereIn('status', ['pending', 'dipinjam'])
            ->exists();

        if ($dipakai) {
            return back()->with('error', 'Buku sedang diproses atau dipinjam');
        }

        $total = Peminjaman::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'dipinjam'])
            ->count();

        if ($total >= 3) {
            return back()->with('error', 'Maksimal 3 buku');
        }

        $sudah = Peminjaman::where('user_id', auth()->id())
            ->where('book_id', $book_id)
            ->whereIn('status', ['pending', 'dipinjam'])
            ->exists();

        if ($sudah) {
            return back()->with('error', 'Kamu sudah request buku ini');
        }

        $pinjam = Peminjaman::create([
            'user_id'         => auth()->id(),
            'book_id'         => $book_id,
            'tanggal_pinjam'  => now(),
            'tanggal_kembali' => now()->addDays($durasi),
            'status'          => 'pending',
        ]);

        // ✅ NOTIF KE USER — konfirmasi request terkirim
        Notifikasi::create([
            'user_id'       => auth()->id(),
            'book_id'       => $book_id,
            'peminjaman_id' => $pinjam->id,
            'pesan'         => 'Permintaan peminjaman buku "' . $book->judul . '" untuk ' . $durasi . ' hari berhasil dikirim dan sedang menunggu persetujuan admin.',
            'dibaca'        => false,
        ]);

        // ✅ NOTIF KE ADMIN — ada request masuk
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin) {
            Notifikasi::create([
                'user_id'       => $admin->id,
                'book_id'       => $book_id,
                'peminjaman_id' => $pinjam->id,
                'pesan'         => 'User ' . auth()->user()->name . ' mengajukan peminjaman buku "' . $book->judul . '" untuk ' . $durasi . ' hari.',
                'dibaca'        => false,
            ]);
        }

        return back()->with('success', 'Permintaan peminjaman ' . $durasi . ' hari berhasil dikirim! Menunggu persetujuan admin.');
    }

    // 🔹 KEMBALIKAN (user upload bukti)
    public function kembalikan(Request $request, $id)
    {
        $request->validate([
            'bukti' => 'required|image|max:2048'
        ]);

        $pinjam = Peminjaman::with('book')->findOrFail($id);

        $file     = $request->file('bukti');
        $namaFile = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/bukti'), $namaFile);

        $pinjam->bukti  = $namaFile;
        $pinjam->status = 'menunggu_verifikasi';
        $pinjam->save();

        // ✅ NOTIF KE USER — bukti terkirim
        Notifikasi::create([
            'user_id'       => $pinjam->user_id,
            'book_id'       => $pinjam->book_id,
            'peminjaman_id' => $pinjam->id,
            'pesan'         => 'Bukti pengembalian buku "' . $pinjam->book->judul . '" berhasil dikirim. Menunggu konfirmasi admin.',
            'dibaca'        => false,
        ]);

        // ✅ NOTIF KE ADMIN — ada bukti masuk
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin) {
            Notifikasi::create([
                'user_id'       => $admin->id,
                'book_id'       => $pinjam->book_id,
                'peminjaman_id' => $pinjam->id,
                'pesan'         => 'User ' . $pinjam->user->name . ' mengirim bukti pengembalian buku "' . $pinjam->book->judul . '".',
                'dibaca'        => false,
            ]);
        }

        return back()->with('success', 'Bukti pengembalian dikirim, menunggu konfirmasi admin');
    }

    // 🔹 KONFIRMASI ADMIN
    public function konfirmasi($id)
{
    $pinjam = Peminjaman::with('book')->findOrFail($id);

    if ($pinjam->status !== 'menunggu_verifikasi') {
        return back()->with('error', 'Belum ada bukti pengembalian');
    }

    // Hitung denda
    $tglKembali     = \Carbon\Carbon::parse($pinjam->tanggal_kembali);
    $tglKonfirmasi  = now();
    $hariTerlambat  = 0;
    $denda          = 0;

    if ($tglKonfirmasi->isAfter($tglKembali)) {
        $hariTerlambat = $tglKonfirmasi->diffInDays($tglKembali);
        $denda         = $hariTerlambat * 1000; // Rp 1.000/hari
    }

    $pinjam->status = 'selesai';
    $pinjam->denda  = $denda;
    $pinjam->save();

    $book = $pinjam->book;
    $book->increment('stock');
    $book->tersedia = true;
    $book->save();

    // Notif ke user
    $pesanDenda = $denda > 0
        ? " Terdapat denda keterlambatan {$hariTerlambat} hari sebesar Rp " . number_format($denda, 0, ',', '.') . '.'
        : ' Tidak ada denda.';

    Notifikasi::create([
        'user_id'       => $pinjam->user_id,
        'book_id'       => $pinjam->book_id,
        'peminjaman_id' => $pinjam->id,
        'pesan'         => 'Pengembalian buku "' . $book->judul . '" telah dikonfirmasi admin.' . $pesanDenda,
        'dibaca'        => false,
    ]);

    return back()->with('success', 'Pengembalian dikonfirmasi' . ($denda > 0 ? " | Denda: Rp " . number_format($denda, 0, ',', '.') : ' | Tidak ada denda'));
}

    // 🔹 ADMIN APPROVE
    public function approve($id)
    {
        $pinjam = Peminjaman::with('book', 'user')->findOrFail($id);

        if ($pinjam->status !== 'pending') {
            return back()->with('error', 'Sudah diproses');
        }

        $dipakai = Peminjaman::where('book_id', $pinjam->book_id)
            ->where('id', '!=', $id)
            ->whereIn('status', ['pending', 'dipinjam'])
            ->exists();

        if ($dipakai) {
            return back()->with('error', 'Buku sudah diambil user lain');
        }

        $book = $pinjam->book;

        if ($book->stock <= 0) {
            return back()->with('error', 'Gagal: Stok buku habis saat diapprove');
        }

        $pinjam->status = 'dipinjam';
        $pinjam->save();

        $book->tersedia = false;
        $book->save();

        //  NOTIF KE USER — peminjaman diapprove
        Notifikasi::create([
            'user_id'       => $pinjam->user_id,
            'book_id'       => $pinjam->book_id,
            'peminjaman_id' => $pinjam->id,
            'pesan'         => '✅ Permintaan peminjaman buku "' . $book->judul . '" telah disetujui admin. Silakan ambil bukunya!',
            'dibaca'        => false,
        ]);

        return back()->with('success', 'Peminjaman disetujui');
    }

    
    public function Reject($id)
{
    $pinjam = Peminjaman::with('book', 'user')->findOrFail($id);

    // 1. Validasi: Jika sudah bukan pending, jangan diproses lagi
    if ($pinjam->status !== 'pending') {
        return back()->with('error', 'Peminjaman ini sudah pernah diproses sebelumnya.');
    }

    // 2. UPDATE STATUS (Diletakkan DI LUAR if validasi)
    $pinjam->status = 'reject';
    $pinjam->save();

    //  NOTIF KE USER
    Notifikasi::create([
        'user_id'       => $pinjam->user_id,
        'book_id'       => $pinjam->book_id,
        'peminjaman_id' => $pinjam->id,
        'pesan'         => '❌ Permintaan peminjaman buku "' . $pinjam->book->judul . '" ditolak admin. Coba pinjam buku lain ya!',
        'dibaca'        => false,
    ]);

    return back()->with('failed', 'Peminjaman telah ditolak.');
}
        










    // 🔹 ADMIN VIEW
    public function admin(Request $request)
    {
        $query = Peminjaman::with('user', 'book');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = \Carbon\Carbon::parse($request->start_date)->startOfDay();
            $end   = \Carbon\Carbon::parse($request->end_date)->endOfDay();
            if ($start->diffInDays($end) > 7) {
                return back()->with('error', 'Rentang tanggal maksimal 7 hari');
            }
            $query->whereBetween('tanggal_pinjam', [$start, $end]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('tanggal_pinjam', '>=', \Carbon\Carbon::parse($request->start_date)->startOfDay());
        } elseif ($request->filled('end_date')) {
            $query->whereDate('tanggal_pinjam', '<=', \Carbon\Carbon::parse($request->end_date)->endOfDay());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->latest()->get()->map(function($p) {
            $now        = now();
            $tglKembali = \Carbon\Carbon::parse($p->tanggal_kembali);
            $p->is_terlambat   = $p->status == 'dipinjam' && $now->isAfter($tglKembali);
            $p->hari_terlambat = $p->is_terlambat ? $now->diffInDays($tglKembali) : 0;
            return $p;
        });

        return view('admin.peminjaman', compact('data'));
    }

    // 🔹 USER RIWAYAT
    public function saya()
    {
        $data = Peminjaman::with('book', 'denda')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pages.peminjaman', compact('data'));
    }

    // 🔹 EXPORT PDF
    public function exportPdf(Request $request)
    {
        $query = Peminjaman::with('user', 'book');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = \Carbon\Carbon::parse($request->start_date)->startOfDay();
            $end   = \Carbon\Carbon::parse($request->end_date)->endOfDay();
            if ($start->diffInDays($end) > 7) {
                return back()->with('error', 'Rentang tanggal maksimal 7 hari untuk export');
            }
            $query->whereBetween('tanggal_pinjam', [$start, $end]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data   = $query->latest()->get();
        $period = $request->start_date
            ? "{$request->start_date} s/d {$request->end_date}"
            : 'Semua Tanggal';

        $pdf = Pdf::loadView('admin.peminjaman_pdf', compact('data', 'period'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('riwayat-peminjaman-' . date('Y-m-d') . '.pdf');
    }
}