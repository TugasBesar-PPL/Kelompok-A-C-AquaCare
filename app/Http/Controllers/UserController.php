<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->orderBy('role')->orderBy('name');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', Rule::in(['admin', 'petugas'])],
            'password' => ['required', Password::min(8)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'role.required' => 'Peran akun wajib dipilih.',
            'password.required' => 'Password awal wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,
            'login_gagal' => 0,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$validated['name']} ({$validated['role']}) berhasil ditambahkan.");
    }

    public function toggleStatus(int $id)
    {
        $user = User::findOrFail($id);

        // UC-03 Alur Alternatif A2: Admin menonaktifkan akunnya sendiri ditolak
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menonaktifkan akun Anda sendiri!']);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusStr = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$user->name} berhasil {$statusStr}.");
    }

    public function updateRole(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'role' => ['required', Rule::in(['admin', 'petugas', 'warga'])],
        ]);

        if ($user->id === Auth::id() && $validated['role'] !== 'admin') {
            return back()->withErrors(['error' => 'Anda tidak dapat menurunkan peran akun Anda sendiri!']);
        }

        $user->role = $validated['role'];
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', "Peran akun {$user->name} berhasil diubah menjadi {$user->role}.");
    }
}
