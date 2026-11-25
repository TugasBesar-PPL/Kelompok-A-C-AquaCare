@extends('layouts.app')

@section('title', 'Baku Mutu Parameter Air - AquaCare')
@section('breadcrumb-parent', Auth::check() ? strtoupper(Auth::user()->role) : 'STANDAR')
@section('breadcrumb-title', 'Baku Mutu Parameter Air')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Top Banner -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
        <div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-cyan-600 block mb-1">
                STANDAR REGULASI & KELAYAKAN AIR (PBI-18)
            </span>
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">
                Batas Baku Mutu Parameter Air
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Nilai acuan penentuan status kelayakan (Layak, Waspada, Tidak Layak) mengacu pada standar baku mutu air bersih.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Standar Aktif
            </span>
        </div>
    </div>

    <!-- Parameter Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-6">Parameter</th>
                        <th class="py-3.5 px-4">Satuan</th>
                        <th class="py-3.5 px-4">Nilai Normal (Min - Maks)</th>
                        <th class="py-3.5 px-4">Batas Waspada (Min - Maks)</th>
                        <th class="py-3.5 px-4">Berlaku Sejak</th>
                        <th class="py-3.5 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse ($parameters as $parameter)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-900 block text-sm">{{ $parameter->nama_parameter }}</span>
                                <span class="text-[11px] text-cyan-600 font-mono uppercase">{{ $parameter->kode_parameter }}</span>
                            </td>
                            <td class="py-4 px-4 text-slate-600 font-mono">
                                {{ $parameter->satuan }}
                            </td>
                            <td class="py-4 px-4 text-slate-800">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 font-semibold font-mono">
                                    {{ number_format((float) $parameter->nilai_minimum, 2, ',', '.') }} - {{ number_format((float) $parameter->nilai_maksimum, 2, ',', '.') }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-slate-800">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 font-semibold font-mono">
                                    {{ number_format((float) $parameter->batas_waspada_min, 2, ',', '.') }} - {{ number_format((float) $parameter->batas_waspada_maks, 2, ',', '.') }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-slate-500">
                                {{ $parameter->tanggal_berlaku ? $parameter->tanggal_berlaku->format('d M Y') : '-' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="inline-flex items-center gap-2">
                                    @if(Auth::check() && Auth::user()->isAdmin())
                                        <a href="{{ route('baku-mutu.admin.edit', $parameter->kode_parameter) }}" 
                                           class="px-3 py-1.5 rounded-lg bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs shadow-xs transition-colors">
                                            <i class="fa-solid fa-pen-to-square mr-1"></i> Ubah
                                        </a>
                                        <a href="{{ route('baku-mutu.admin.history', $parameter->kode_parameter) }}" 
                                           class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs border border-slate-200 transition-colors">
                                            <i class="fa-solid fa-clock-rotate-left mr-1 text-slate-400"></i> Riwayat
                                        </a>
                                    @else
                                        <a href="{{ route('baku-mutu.history', $parameter->kode_parameter) }}" 
                                           class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs border border-slate-200 transition-colors">
                                            <i class="fa-solid fa-clock-rotate-left mr-1 text-slate-400"></i> Riwayat
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Belum ada data baku mutu parameter air.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
