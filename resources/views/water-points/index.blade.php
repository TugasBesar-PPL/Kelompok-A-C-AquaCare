@extends('layouts.app')

@section('title', 'AquaCare - Manajemen Titik Sumber Air (PBI-01)')
@section('breadcrumb', 'Titik Sumber Air')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold" style="background-color: #e0f2fe; color: #075985;">
                    PBI-01 &bull; PIC: Fadhlan S.
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                    Use Case UC-01 s/d UC-04
                </span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Manajemen Titik Sumber Air</h1>
            <p class="text-xs text-slate-500 mt-1 max-w-2xl">
                Kelola data induk titik sumber air (tambah, ubah, hapus) sebagai lokasi pemantauan kualitas air bersih di wilayah.
            </p>
        </div>
        <a href="{{ route('water-points.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-white font-bold text-xs rounded-lg shadow-sm transition-colors shrink-0"
           style="background-color: #0284c7;">
            <i class="fa-solid fa-plus text-sm"></i>
            <span>+ Tambah Titik Air</span>
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('water-points.index') }}" class="flex flex-col md:flex-row items-center gap-3">
            <div class="relative w-full md:w-64">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nama / alamat..."
                    class="w-full text-xs border border-slate-300 rounded-lg pl-8 pr-3 py-2 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>
            <select name="jenis" class="text-xs border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-sky-500 w-full md:w-auto">
                <option value="">Semua Jenis</option>
                @foreach($daftarJenis as $jns)
                    <option value="{{ $jns }}" {{ ($filters['jenis'] ?? '') == $jns ? 'selected' : '' }}>{{ $jns }}</option>
                @endforeach
            </select>
            <select name="wilayah" class="text-xs border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-sky-500 w-full md:w-auto">
                <option value="">Semua Wilayah</option>
                @foreach($daftarWilayah as $wil)
                    <option value="{{ $wil }}" {{ ($filters['wilayah'] ?? '') == $wil ? 'selected' : '' }}>{{ $wil }}</option>
                @endforeach
            </select>
            <select name="status" class="text-xs border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-sky-500 w-full md:w-auto">
                <option value="">Semua Status</option>
                <option value="Layak" {{ ($filters['status'] ?? '') == 'Layak' ? 'selected' : '' }}>Layak</option>
                <option value="Waspada" {{ ($filters['status'] ?? '') == 'Waspada' ? 'selected' : '' }}>Waspada</option>
                <option value="Tidak Layak" {{ ($filters['status'] ?? '') == 'Tidak Layak' ? 'selected' : '' }}>Tidak Layak</option>
            </select>
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="flex-1 md:flex-none px-4 py-2 text-xs font-bold text-white rounded-lg" style="background-color: #1e293b;">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('water-points.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg text-center transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Data -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[11px]" style="background-color: #f8fafc;">
                        <th class="py-3.5 px-5">Nama Titik</th>
                        <th class="py-3.5 px-5">Jenis</th>
                        <th class="py-3.5 px-5">Wilayah &amp; Alamat</th>
                        <th class="py-3.5 px-5">Koordinat</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5">Uji Terakhir</th>
                        <th class="py-3.5 px-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($waterPoints as $point)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-5 font-semibold text-slate-900">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style="background-color: #e0f2fe;">
                                        <i class="fa-solid fa-faucet-drip text-[11px]" style="color: #0284c7;"></i>
                                    </div>
                                    {{ $point->nama }}
                                </div>
                            </td>
                            <td class="py-3.5 px-5">
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $point->jenis }}
                                </span>
                            </td>
                            <td class="py-3.5 px-5 text-slate-600">
                                <div class="font-semibold text-slate-800">{{ $point->wilayah }}</div>
                                <div class="text-[11px] text-slate-400 truncate max-w-[200px]">{{ $point->alamat }}</div>
                            </td>
                            <td class="py-3.5 px-5 text-slate-500 font-mono text-[11px]">
                                <div>{{ number_format($point->latitude, 5) }}</div>
                                <div>{{ number_format($point->longitude, 5) }}</div>
                            </td>
                            <td class="py-3.5 px-5">
                                @if($point->status_terkini == 'Layak')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Layak
                                    </span>
                                @elseif($point->status_terkini == 'Waspada')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Waspada
                                    </span>
                                @elseif($point->status_terkini == 'Tidak Layak')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-800 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Tidak Layak
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> {{ $point->status_terkini ?? 'Belum Diuji' }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-slate-500 text-[11px]">
                                {{ $point->pengukuran_terakhir ? $point->pengukuran_terakhir->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('water-points.edit', $point->titik_air_id) }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-bold rounded-lg transition-colors border"
                                       style="color: #0284c7; border-color: #bae6fd; background-color: #f0f9ff;">
                                        <i class="fa-solid fa-pen-to-square"></i> Ubah
                                    </a>
                                    <form method="POST" action="{{ route('water-points.destroy', $point->titik_air_id) }}"
                                          onsubmit="return confirm('Yakin hapus titik air ini? Pastikan belum ada riwayat pengukuran.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-bold rounded-lg transition-colors border"
                                                style="color: #be123c; border-color: #fecdd3; background-color: #fff1f2;">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-faucet-drip text-3xl mb-3 block" style="color: #bae6fd;"></i>
                                <p class="font-medium">Belum ada data titik sumber air.</p>
                                <p class="text-[11px] mt-1">Mulai dengan menambahkan titik air baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($waterPoints->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $waterPoints->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
