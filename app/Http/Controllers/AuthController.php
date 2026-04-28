<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    // 🔹 LOGIN USER (GET - tampilkan form)
    public function loginUser()
    {
        return view('auth.login');
    }

    //  PROSES LOGIN USER (POST)
    public function loginUserPost(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $data = $request->only('email', 'password');

        if (Auth::attempt($data)) {
            // 🔥 CEK ROLE: Admin nggak boleh login di halaman user
            if (Auth::user()->role == 'admin') {
                Auth::logout(); // ← ✅ FIX: Pakai Auth::logout()
                return back()->with('error', 'Akun admin tidak bisa login di sini. Gunakan /login-admin');
            }
            return redirect('/');
        }

        return back()->with('error', 'Email atau password salah');
    }

    // 🔹 LOGIN ADMIN (GET - tampilkan form)
    public function loginAdmin()
    {
        return view('auth.login-admin');
    }

    // 🔹 PROSES LOGIN ADMIN (POST)
    public function loginAdminPost(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $data = $request->only('email', 'password');

        if (Auth::attempt($data)) {
            // 🔥 CEK ROLE: Hanya admin yang boleh login di sini
            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                return back()->with('error', 'Akses ditolak. Hanya admin yang bisa login di sini');
            }
            return redirect('/dashboard');
        }

        return back()->with('error', 'Email atau password salah');
    }

    // 🔹 REGISTER
    public function register()
    {
        return view('auth.register');
    }

    // 🔹 PROSES REGISTER
    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'kelas' => 'required',
            'password' => 'required|min:3',
            'nis' => 'required|unique:users,nis',
            'no_telp' => 'required|numeric|unique:users,no_telp',
           
        ]);

        $user = new \App\Models\User;
        $user->role = 'user';

        $user->name = $request->name;
        $user->email = $request->email;
        $user->kelas = $request->kelas;
        $user->nis = $request->nis;
        $user->no_telp = $request->no_telp;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login');
    }

    // 🔹 LOGOUT (untuk semua)
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/login');
    }
}