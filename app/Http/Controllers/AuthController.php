<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // 1. Cek apakah user terkunci karena 5x gagal login
        if ($user && $user->terkunci_sampai && Carbon::now()->lessThan($user->terkunci_sampai)) {
            $sisaMenit = Carbon::now()->diffInMinutes($user->terkunci_sampai, false) + 1;
            return back()->withErrors([
                'email' => "Akun Anda terkunci sementara karena 5 kali gagal login. Silakan coba lagi dalam {$sisaMenit} menit.",
            ])->onlyInput('email');
        }

        // 2. Cek kredensial
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            if ($user) {
                $user->increment('login_gagal');
                if ($user->login_gagal >= 5) {
                    $user->update([
                        'terkunci_sampai' => Carbon::now()->addMinutes(15),
                        'login_gagal' => 0,
                    ]);
                    return back()->withErrors([
                        'email' => 'Login gagal 5 kali berturut-turut. Akun Anda dikunci selama 15 menit demi keamanan.',
                    ])->onlyInput('email');
                }
            }

            return back()->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        // 3. Cek apakah akun aktif
        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
            ])->onlyInput('email');
        }

        // 4. Berhasil login: reset failure counter
        $user->update([
            'login_gagal' => 0,
            'terkunci_sampai' => null,
        ]);

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectBasedOnRole($user)
            ->with('success', "Selamat datang kembali, {$user->name}!");
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain atau masuk.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'warga', // FD-02: Peran akun yang terbentuk selalu warga
            'is_active' => true,
            'login_gagal' => 0,
        ]);

        return redirect()->route('login')
            ->with('success', 'Pendaftaran akun warga berhasil! Silakan masuk dengan akun Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }

    /**
     * Demo Quick Switcher untuk pengujian peran (Admin / Petugas / Warga)
     */
    public function quickLogin($role = 'admin')
    {
        $user = User::where('role', $role)->where('is_active', true)->first();

        if ($user) {
            Auth::login($user);
            request()->session()->regenerate();
            return $this->redirectBasedOnRole($user)
                ->with('success', "Masuk sebagai {$user->name} (Peran: " . ucfirst($user->role) . ")");
        }

        return redirect()->route('login')->withErrors(['email' => "Pengguna dengan peran {$role} tidak ditemukan."]);
    }

    private function redirectBasedOnRole(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('baku-mutu.admin.index');
        }
        return redirect()->route('baku-mutu.index');
    }
}
