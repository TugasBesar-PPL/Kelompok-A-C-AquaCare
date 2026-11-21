<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))->with('success', "Selamat datang kembali, {$user->name}!");
    }

    /**
     * Quick Switch / Demo Login helper for development & assessment
     */
    public function quickLogin($role = 'admin')
    {
        $user = User::where('role', $role)->where('is_active', true)->first();
        if ($user) {
            Auth::login($user);
            request()->session()->regenerate();
            return redirect()->route('dashboard')->with('success', "Masuk sebagai {$user->name} ({$user->role})");
        }
        return redirect()->route('login')->withErrors(['email' => "Pengguna role {$role} tidak ditemukan."]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}

