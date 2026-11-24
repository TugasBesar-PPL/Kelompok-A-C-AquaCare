@extends('layouts.app')

@section('title', 'Riwayat Baku Mutu: ' . ($history->first()->nama_parameter ?? '') . ' - AquaCare Admin')
@section('breadcrumb-parent', 'ADMIN')
@section('breadcrumb-title', 'Riwayat Versi Parameter')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Banner -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
        <div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-cyan-600 block mb-1">
                HISTORI REGULASI (PBI-18)
            </span>
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">
                Riwayat Baku Mutu: {{ $history->first()->nama_parameter ?? '-' }}
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Setiap versi mencatat batas nilai yang berlaku pada periode tertentu untuk keperluan audit dan evaluasi data lama.
            </p>
        </div>

        <a href="{{ route('baku-mutu.admin.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition-colors shadow-xs">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Daftar</span>
        </a>
    </div>

    <!-- History Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-6">Tanggal Berlaku</th>
                        <th class="py-3.5 px-4">Nilai Normal (Min - Maks)</th>
                        <th class="py-3.5 px-4">Batas Waspada (Min - Maks)</th>
                        <th class="py-3.5 px-4">Diubah Oleh (User ID)</th>
                        <th class="py-3.5 px-6 text-center">Status Versi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @foreach ($history as $index => $version)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-900">
                                {{ $version->tanggal_berlaku ? $version->tanggal_berlaku->format('d M Y') : '-' }}
                            </td>
                            <td class="py-4 px-4 text-slate-800">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 font-semibold font-mono">
                                    {{ number_format((float) $version->nilai_minimum, 2, ',', '.') }} - {{ number_format((float) $version->nilai_maksimum, 2, ',', '.') }} {{ $version->satuan }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-slate-800">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-amber-50 border border-amber-200 text-amber-800 font-semibold font-mono">
                                    {{ number_format((float) $version->batas_waspada_min, 2, ',', '.') }} - {{ number_format((float) $version->batas_waspada_maks, 2, ',', '.') }} {{ $version->satuan }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-slate-500 font-mono">
                                {{ $version->diubah_oleh ? 'User #' . $version->diubah_oleh : 'Sistem / Seeder' }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($index === 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-cyan-50 border border-cyan-200 text-cyan-700">
                                        Versi Terkini
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-500">
                                        Arsip
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
