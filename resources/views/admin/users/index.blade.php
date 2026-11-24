@extends('layouts.app')

@section('title', 'Kelola Akun & Hak Akses - AquaCare Admin')
@section('breadcrumb-parent', 'ADMIN')
@section('breadcrumb-title', 'Kelola Akun Pengguna')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    <!-- Top Info & Add User -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Add User Form (FD-03) -->
        <div class="lg:col-span-1 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
            <span class="text-[11px] font-bold uppercase tracking-wider text-cyan-600 block mb-1">
                TAMBAH AKUN PETUGAS (FD-03)
            </span>
            <h2 class="text-lg font-bold text-slate-900 tracking-tight mb-4">
                Buat Akun Baru
            </h2>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Lengkap
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-xs text-slate-800"
                           placeholder="Nama Petugas / Pengelola">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Email Resmi
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-xs text-slate-800"
                           placeholder="nama@aquacare.id">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        No. Telepon / WhatsApp (Opsional)
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-xs text-slate-800"
                           placeholder="08123456789">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Peran Akun
                    </label>
                    <select name="role" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-xs text-slate-800 bg-white">
                        <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas Lapangan</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Password Awal (Min 8 Karakter)
                    </label>
                    <input type="password" name="password" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-xs text-slate-800"
                           placeholder="••••••••">
                </div>

                <button type="submit"
                        class="w-full py-2.5 px-4 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs shadow-md shadow-cyan-600/20 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Simpan Akun Baru</span>
                </button>
            </form>
        </div>

        <!-- Filter & Statistics -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
                <span class="text-[11px] font-bold uppercase tracking-wider text-cyan-600 block mb-1">
                    OTORISASI & KEAMANAN
                </span>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">
                    Daftar Akun Pengguna Terdaftar
                </h1>
                <p class="text-xs text-slate-500 mt-1 mb-4">
                    Kelola hak akses pengguna sesuai peran: <strong>Admin</strong>, <strong>Petugas Lapangan</strong>, dan <strong>Warga</strong>. Admin dapat menonaktifkan akun yang melanggar.
                </p>

                <!-- Filter Controls -->
                <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <select name="role" onchange="this.form.submit()"
                            class="px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-700 bg-white">
                        <option value="">-- Semua Peran --</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="petugas" {{ request('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="warga" {{ request('role') === 'warga' ? 'selected' : '' }}>Warga</option>
                    </select>

                    <select name="is_active" onchange="this.form.submit()"
                            class="px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-700 bg-white">
                        <option value="">-- Semua Status --</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>

                    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 text-center rounded-xl border border-slate-200 text-xs text-slate-600 hover:bg-slate-50 font-semibold">
                        Reset Filter
                    </a>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="py-3 px-4">Pengguna</th>
                                <th class="py-3 px-3">Peran</th>
                                <th class="py-3 px-3 text-center">Status</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium">
                            @forelse($users as $user)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-3.5 px-4">
                                        <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $user->email }}</div>
                                        @if($user->phone)
                                            <div class="text-[10px] text-slate-400">{{ $user->phone }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-3">
                                        @php
                                            $roleBadge = match($user->role) {
                                                'admin' => 'bg-purple-100 text-purple-700 border-purple-200',
                                                'petugas' => 'bg-cyan-100 text-cyan-700 border-cyan-200',
                                                default => 'bg-slate-100 text-slate-700 border-slate-200'
                                            };
                                        @endphp
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $roleBadge }} uppercase">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-3 text-center">
                                        @if($user->is_active)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if($user->id !== Auth::id())
                                            <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                @if($user->is_active)
                                                    <button type="submit" onclick="return confirm('Nonaktifkan akun ini?')"
                                                            class="px-2.5 py-1 text-[11px] rounded-lg bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 font-semibold transition-colors">
                                                        Nonaktifkan
                                                    </button>
                                                @else
                                                    <button type="submit"
                                                            class="px-2.5 py-1 text-[11px] rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 font-semibold transition-colors">
                                                        Aktifkan
                                                    </button>
                                                @endif
                                            </form>
                                        @else
                                            <span class="text-[11px] text-slate-400 italic">Akun Anda</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-400">
                                        Tidak ada akun pengguna yang sesuai dengan filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="px-4 py-3 border-t border-slate-200 bg-slate-50/50">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
