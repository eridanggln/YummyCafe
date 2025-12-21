<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1. Coba login dengan email dan password
        if (Auth::attempt($credentials)) {

            // 2. Cek apakah user yang login statusnya aktif (1)
            if (Auth::user()->is_active == 0) {
                Auth::logout(); // Paksa keluar karena akun nonaktif
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->with('login_error', 'Akun Anda telah dinonaktifkan.<br>Silakan hubungi admin.');
            }

            // 3. Jika aktif, lanjut ke dashboard
            $request->session()->regenerate();
            return redirect('dashboard');
        }

        // ❌ Login gagal (Email/Password salah)
        return back()->with('login_error', 'Email atau password salah');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
