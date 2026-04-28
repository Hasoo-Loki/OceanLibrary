<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Kategori;

class AdminController extends Controller
{
   


public function dashboard()
{
    $totalBuku = Book::count();
    $totalUser = User::count();
    $totalPinjam = Peminjaman::count();

    $books = Book::latest()->get();
    $kategoris = Kategori::all();

    return view('admin.dashboard', compact(
        'totalBuku',
        'totalUser',
        'totalPinjam',
        'books',
        'kategoris'
    ));
}
    // ================= CREATE ADMIN =================
    public function createAdmin(Request $request)
    {
        $totalAdmin = User::where('role', 'admin')->count();

        if ($totalAdmin >= 5) {
            return back()->with('error', 'Admin sudah mencapai batas (max 5)');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'kelas' => '-',
            'nis' => '-',
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        return back()->with('success', 'Admin berhasil dibuat');
    }

    // ================= APPROVE PEMINJAMAN =================
    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status = 'dikembalikan';
        $peminjaman->save();

        return back()->with('success', 'Peminjaman berhasil disetujui');
    }

    public function toggleMember ($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->is_member = !$user->is_member;
        $user->save();

        return back()->with('success', 'Status member berhasil diubah');
    }

    public function users()
    {
        $users =\App\Models\User::latest()->get();
        return view('admin.users', compact('users'));
    }

    public function Reject($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->status = 'Reject';
        $peminjaman->save();

        return back()->with('failed.', 'Peminjaman ditolak');
    }


    public function kategori()
    {
        $data =\App\Models\Kategori::latest()->get();
        return view('admin.kategori', compact('data'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);
        Kategori::create([
            'nama' => $request->nama
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan');
    }

}