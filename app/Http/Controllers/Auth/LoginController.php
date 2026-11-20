<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller login/logout minimal untuk mendukung autentikasi.
 *
 * Catatan: Controller ini menyediakan login/logout dasar yang dibutuhkan
 * agar PBI-17 dan fitur lain yang memerlukan autentikasi dapat berjalan.
 * PIC Fitur Dasar (FD-01) dapat memperluas controller ini dengan
 * fitur lockout 5x gagal, token 2 jam, dll sesuai PRD.
 */
class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect berdasarkan role
            return match ($user->role) {
                'admin' => redirect()->intended('/admin/dashboard'),
                'petugas' => redirect()->intended('/petugas/tugas'),
                'warga' => redirect()->intended('/warga/beranda'),
                default => redirect()->intended('/'),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
