@extends('layouts.admin')

@section('title', 'Log Aktivitas')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700 transition-colors">Dashboard</a>
    <span class="mx-1.5">/</span>
    <span class="text-gray-700 font-medium">Log Aktivitas</span>
@endsection

@section('content')
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-800">Log Aktivitas</h1>
        <p class="text-sm text-gray-500 mt-1">Pantau dan telusuri aktivitas penting yang terjadi di sistem AquaCare.</p>
    </div>

    {{-- Filter Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-4">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" id="filter-form">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                {{-- Filter Tanggal Mulai --}}
                <div>
                    <label for="from" class="block text-xs font-medium text-gray-600 mb-1">Tanggal Mulai</label>
                    <input type="date" name="from" id="from" value="{{ request('from') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1a3a5c]/20 focus:border-[#1a3a5c] transition">
                </div>

                {{-- Filter Tanggal Akhir --}}
                <div>
                    <label for="to" class="block text-xs font-medium text-gray-600 mb-1">Tanggal Akhir</label>
                    <input type="date" name="to" id="to" value="{{ request('to') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1a3a5c]/20 focus:border-[#1a3a5c] transition">
                </div>

                {{-- Filter Pengguna --}}
                <div>
                    <label for="user_id" class="block text-xs font-medium text-gray-600 mb-1">Pengguna</label>
                    <select name="user_id" id="user_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1a3a5c]/20 focus:border-[#1a3a5c] transition bg-white">
                        <option value="">Semua Pengguna</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ ucfirst($user->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Aksi --}}
                <div>
                    <label for="aksi" class="block text-xs font-medium text-gray-600 mb-1">Jenis Aksi</label>
                    <select name="aksi" id="aksi"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1a3a5c]/20 focus:border-[#1a3a5c] transition bg-white">
                        <option value="">Semua Aksi</option>
                        @foreach ($aksiList as $aksi)
                            <option value="{{ $aksi }}" {{ request('aksi') === $aksi ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $aksi)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Filter buttons --}}
            <div class="flex items-center gap-2 mt-3">
                <button type="submit"
                        class="px-4 py-2 bg-[#1a3a5c] hover:bg-[#152e49] text-white text-sm font-medium rounded-lg transition-colors">
                    Terapkan Filter
                </button>
                <a href="{{ route('admin.audit-logs.index') }}"
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    Reset Filter
                </a>

                {{-- Active filter indicator --}}
                @if (request()->hasAny(['from', 'to', 'user_id', 'aksi']))
                    <span class="text-xs text-[#0d9488] font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter aktif
                    </span>
                @endif
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if ($logs->isEmpty())
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-16 px-4">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if (request()->hasAny(['from', 'to', 'user_id', 'aksi']))
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Tidak ada hasil</h3>
                    <p class="text-sm text-gray-500 text-center max-w-sm">Tidak ditemukan log aktivitas yang sesuai dengan filter yang diterapkan. Coba ubah kriteria filter Anda.</p>
                @else
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Belum ada aktivitas</h3>
                    <p class="text-sm text-gray-500 text-center max-w-sm">Log aktivitas akan muncul di sini saat ada perubahan data penting dalam sistem.</p>
                @endif
            </div>
        @else
            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50/50">
                            <th class="text-left text-xs font-semibold text-gray-600 px-4 py-3">Waktu</th>
                            <th class="text-left text-xs font-semibold text-gray-600 px-4 py-3">Pelaku</th>
                            <th class="text-left text-xs font-semibold text-gray-600 px-4 py-3">Aksi</th>
                            <th class="text-left text-xs font-semibold text-gray-600 px-4 py-3">Entitas</th>
                            <th class="text-left text-xs font-semibold text-gray-600 px-4 py-3">ID Entitas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($logs as $log)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                {{-- Waktu --}}
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-800">{{ $log->waktu->translatedFormat('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $log->waktu->format('H:i') }}</div>
                                </td>

                                {{-- Pelaku --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-[#1a3a5c]/10 flex items-center justify-center text-xs font-semibold text-[#1a3a5c] shrink-0">
                                            {{ strtoupper(substr($log->user->name ?? '?', 0, 2)) }}
                                        </div>
                                        <span class="text-sm text-gray-800">{{ $log->user->name ?? '-' }}</span>
                                    </div>
                                </td>

                                {{-- Aksi (badge) --}}
                                <td class="px-4 py-3">
                                    @php
                                        $badgeClasses = match($log->aksi) {
                                            'create' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'update' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'delete' => 'bg-red-50 text-red-700 border-red-200',
                                            'verify' => 'bg-teal-50 text-teal-700 border-teal-200',
                                            'reject' => 'bg-orange-50 text-orange-700 border-orange-200',
                                            'assign' => 'bg-purple-50 text-purple-700 border-purple-200',
                                            'status_change' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            default => 'bg-gray-50 text-gray-700 border-gray-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium border {{ $badgeClasses }}">
                                        {{ ucfirst(str_replace('_', ' ', $log->aksi)) }}
                                    </span>
                                </td>

                                {{-- Entitas --}}
                                <td class="px-4 py-3">
                                    <span class="text-sm text-gray-700">{{ ucfirst(str_replace('_', ' ', $log->entitas)) }}</span>
                                </td>

                                {{-- ID Entitas --}}
                                <td class="px-4 py-3">
                                    <span class="text-sm text-gray-500 font-mono">#{{ $log->entitas_id }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($logs->hasPages())
                <div class="border-t border-gray-200 px-4 py-3">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                        <p class="text-xs text-gray-500">
                            Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} log
                        </p>
                        <div class="flex items-center gap-1">
                            {{-- Previous --}}
                            @if ($logs->onFirstPage())
                                <span class="px-3 py-1.5 text-xs text-gray-400 bg-gray-50 rounded-lg cursor-not-allowed">Sebelumnya</span>
                            @else
                                <a href="{{ $logs->previousPageUrl() }}" class="px-3 py-1.5 text-xs text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Sebelumnya</a>
                            @endif

                            {{-- Page numbers --}}
                            @foreach ($logs->getUrlRange(max(1, $logs->currentPage() - 2), min($logs->lastPage(), $logs->currentPage() + 2)) as $page => $url)
                                @if ($page == $logs->currentPage())
                                    <span class="px-3 py-1.5 text-xs font-medium text-white bg-[#1a3a5c] rounded-lg">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="px-3 py-1.5 text-xs text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">{{ $page }}</a>
                                @endif
                            @endforeach

                            {{-- Next --}}
                            @if ($logs->hasMorePages())
                                <a href="{{ $logs->nextPageUrl() }}" class="px-3 py-1.5 text-xs text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Berikutnya</a>
                            @else
                                <span class="px-3 py-1.5 text-xs text-gray-400 bg-gray-50 rounded-lg cursor-not-allowed">Berikutnya</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection
