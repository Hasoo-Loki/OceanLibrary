<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\Denda;
use App\Models\Rating;
use App\Models\Notifikasi;

class BookController extends Controller
{
    public function admin()
    {
        $books = Book::latest()->get();
        return view('admin.books', compact('books'));
    }

    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        $books = $query->latest()->get();

        $kategoris = Book::select('kategori')
            ->distinct()
            ->pluck('kategori');

        return view('pages.books', compact('books', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'    => 'required',
            'penulis'  => 'required',
            'kategori' => 'required',
            'tahun'    => 'required|integer',
            'sinopsis' => 'required',
            'gambar'   => 'required|image',
            'stock'    => 'required|integer|min:0',
        ]);

        $file     = $request->file('gambar');
        $namaFile = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images'), $namaFile);

        Book::create([
            'judul'    => $request->judul,
            'penulis'  => $request->penulis,
            'kategori' => $request->kategori,
            'tahun'    => $request->tahun,
            'sinopsis' => $request->sinopsis,
            'gambar'   => $namaFile,
            'stock'    => $request->stock ?? 1,
            'tersedia' => true,
        ]);

        return back()->with('success', 'Buku berhasil ditambahkan');
    }

    public function edit($id)
    {
        $book     = Book::findOrFail($id);
        $kategoris = \App\Models\Kategori::all();
        return view('admin.edit_book', compact('book', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'judul'    => 'required',
            'penulis'  => 'required',
            'kategori' => 'required',
            'tahun'    => 'required|integer',
            'sinopsis' => 'required',
            'gambar'   => 'nullable|image|max:2048',
            'stock'    => 'required|integer|min:0',
        ]);

        $data = [
            'judul'    => $request->judul,
            'penulis'  => $request->penulis,
            'kategori' => $request->kategori,
            'tahun'    => $request->tahun,
            'sinopsis' => $request->sinopsis,
            'stock'    => $request->stock ?? 1,
        ];

        if ($request->hasFile('gambar')) {
            $file     = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $namaFile);
            $data['gambar'] = $namaFile;
        }

        $book->update($data);

        return redirect('/admin/books')->with('success', 'Buku berhasil diupdate');
    }

    public function delete($id)
    {
        Book::findOrFail($id)->delete();
        return back()->with('success', 'Buku dihapus');
    }

   public function home(Request $request)
{
    $query = Book::with('ratings')->latest();

    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('judul', 'like', '%' . $request->search . '%')
              ->orWhere('penulis', 'like', '%' . $request->search . '%');
        });
    }

    $books     = $query->get();
    $totalBuku = Book::count();
    $totalUser = \App\Models\User::count();

    return view('pages.home', compact('books', 'totalBuku', 'totalUser'));
}

    public function show($id)
    {
        $book  = Book::with('ratings', 'favorites')->findOrFail($id);
        $avg   = $book->ratings->avg('nilai') ?? 0;
        $count = $book->ratings->count();
        return view('pages.detail', compact('book', 'avg', 'count'));
    }

    public function rating(Request $request, $id)
    {
        $request->validate(['nilai' => 'required|integer|min:1|max:5']);

        Rating::updateOrCreate(
            ['user_id' => auth()->id(), 'book_id' => $id],
            ['nilai'   => $request->nilai]
        );

        return back()->with('success', 'Rating berhasil disimpan');
    }

    public function toggleFavorite($id)
    {
        $userId = auth()->id();

        $cek = \App\Models\Favorite::where('user_id', $userId)
            ->where('book_id', $id)
            ->first();

        if ($cek) {
            $cek->delete();
            return back()->with('success', 'Buku dihapus dari favorit');
        } else {
            \App\Models\Favorite::create(['user_id' => $userId, 'book_id' => $id]);
            return back()->with('success', 'Buku ditambahkan ke favorit');
        }
    }

    public function pinjam($book_id)
    {
        // ❗ CEK MEMBER
        if (!auth()->user()->is_member) {
            Notifikasi::create([
                'user_id' => auth()->id(),
                'book_id' => $book_id,
                'pesan'   => 'Kamu belum menjadi member. Hubungi admin untuk mengaktifkan membership agar bisa meminjam buku.',
                'dibaca'  => false,
            ]);
            return back()->with('error', 'Kamu belum menjadi member. Hubungi admin untuk aktivasi membership.');
        }

        $book = Book::findOrFail($book_id);

        //  CEK STOCK
        if ($book->stock <= 0) {
            return back()->with('error', 'Stok buku habis');
        }

        //  CEK BUKU SUDAH DIPROSES
        $dipakai = Peminjaman::where('book_id', $book_id)
            ->whereIn('status', ['pending', 'dipinjam'])
            ->exists();

        if ($dipakai) {
            return back()->with('error', 'Buku sedang diproses atau dipinjam');
        }

        //  LIMIT 3 BUKU
        $total = Peminjaman::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'dipinjam'])
            ->count();

        if ($total >= 3) {
            return back()->with('error', 'Maksimal peminjaman 3 buku sekaligus');
        }

        //  CEGAH DUPLIKAT
        $sudah = Peminjaman::where('user_id', auth()->id())
            ->where('book_id', $book_id)
            ->whereIn('status', ['pending', 'dipinjam'])
            ->exists();

        if ($sudah) {
            return back()->with('error', 'Kamu sudah mengajukan permintaan untuk buku ini');
        }

        $peminjaman = Peminjaman::create([
            'user_id'         => auth()->id(),
            'book_id'         => $book_id,
            'tanggal_pinjam'  => now(),
            'tanggal_kembali' => now()->addDays(7),
            'status'          => 'pending',
        ]);

        //  KURANGI STOCK
        $book->stock -= 1;
        $book->save();

        // NOTIFIKASI KE USER
        Notifikasi::create([
            'user_id'       => auth()->id(),
            'book_id'       => $book_id,
            'peminjaman_id' => $peminjaman->id,
            'pesan'         => 'Permintaan peminjaman buku "' . $book->judul . '" berhasil dikirim dan sedang menunggu persetujuan admin.',
            'dibaca'        => false,
        ]);

        return back()->with('success', 'Permintaan peminjaman berhasil dikirim! Menunggu persetujuan admin.');
    }

    //  TAMPIL DENDA USER
    public function denda(Request $request)
    {
        $query = Denda::where('user_id', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $data = $query->latest()->get();

        $totalBelumLunas = Denda::where('user_id', auth()->id())
            ->where('status', 'belum_lunas')
            ->sum('jumlah');
        
        $totalLunas = Denda::where('user_id', auth()->id())
            ->where('status', 'lunas')
            ->sum('jumlah');

        return view('pages.denda', compact('data', 'totalBelumLunas', 'totalLunas'));
    }
}