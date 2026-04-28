<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    public function index(Request $request)
    {
        $query = Denda::with('user', 'peminjaman.book');

        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data       = $query->latest()->get();
        $users      = User::where('role', 'user')->orderBy('name')->get();
        $peminjaman = Peminjaman::with('user', 'book')
                        ->whereIn('status', ['dipinjam', 'selesai', 'menunggu_verifikasi'])
                        ->latest()->get();

        $totalBelumLunas = Denda::where('status', 'belum_lunas')->sum('jumlah');
        $totalLunas      = Denda::where('status', 'lunas')->sum('jumlah');

        return view('admin.denda', compact(
            'data', 'users', 'peminjaman',
            'totalBelumLunas', 'totalLunas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'jenis'         => 'required|in:terlambat,rusak,hilang',
            'jumlah'        => 'required|integer|min:1000',
            'keterangan'    => 'nullable|string|max:500',
            'peminjaman_id' => 'nullable|exists:peminjaman,id',
            'bukti'         => 'nullable|image|max:2048',
        ]);

        $buktiFile = null;
        
        // Handle file upload untuk bukti
        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $buktiFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/bukti_denda'), $buktiFile);
        }

        $denda = Denda::create([
            'user_id'       => $request->user_id,
            'peminjaman_id' => $request->peminjaman_id ?: null,
            'jenis'         => $request->jenis,
            'jumlah'        => $request->jumlah,
            'keterangan'    => $request->keterangan,
            'bukti'         => $buktiFile,
            'status'        => 'belum_lunas',
        ]);

        // Notif ke user
        $labelJenis = ['terlambat' => 'keterlambatan', 'rusak' => 'kerusakan buku', 'hilang' => 'kehilangan buku'];
        Notifikasi::create([
            'user_id' => $request->user_id,
            'pesan'   => '⚠️ Kamu mendapat denda ' . $labelJenis[$request->jenis] .
                         ' sebesar Rp ' . number_format($request->jumlah, 0, ',', '.') .
                         ($request->keterangan ? '. Ket: ' . $request->keterangan : '') .
                         '. Segera lunasi ke admin.',
            'dibaca'  => false,
        ]);

        return back()->with('success', 'Denda berhasil ditambahkan & notifikasi dikirim ke user.');
    }

    public function lunas($id)
    {
        $denda = Denda::findOrFail($id);
        $denda->status = 'lunas';
        $denda->save();

        Notifikasi::create([
            'user_id' => $denda->user_id,
            'pesan'   => '✅ Denda sebesar Rp ' . number_format($denda->jumlah, 0, ',', '.') .
                         ' telah dikonfirmasi lunas oleh admin. Terima kasih!',
            'dibaca'  => false,
        ]);

        return back()->with('success', 'Denda ditandai lunas.');
    }

    public function destroy($id)
    {
        Denda::findOrFail($id)->delete();
        return back()->with('success', 'Data denda dihapus.');
    }
}