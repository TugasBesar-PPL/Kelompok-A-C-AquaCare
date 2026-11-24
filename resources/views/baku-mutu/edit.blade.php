@extends('layouts.app')

@section('title', 'Ubah Baku Mutu: ' . $parameter->nama_parameter . ' - AquaCare Admin')
@section('breadcrumb-parent', 'ADMIN')
@section('breadcrumb-title', 'Ubah Baku Mutu: ' . $parameter->nama_parameter)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/80 p-8 shadow-sm">
        
        <!-- Header Section -->
        <div class="mb-8">
            <span class="text-[11px] font-bold uppercase tracking-wider text-cyan-600 block mb-1">
                PEMBARUAN STANDAR (PBI-18)
            </span>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                Ubah Baku Mutu: {{ $parameter->nama_parameter }}
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Setiap perubahan akan disimpan sebagai versi baru dengan tanggal berlaku. Versi sebelumnya tetap tersimpan di riwayat.
            </p>
        </div>

        <form method="POST" action="{{ route('baku-mutu.admin.update', $parameter->kode_parameter) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Grid 1: Nilai Minimum & Maksimum Normal -->
            <div class="p-5 rounded-2xl bg-slate-50/70 border border-slate-200/70 space-y-4">
                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider block">
                    1. Rentang Nilai Normal (Kondisi Layak Minum)
                </span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Nilai Minimum ({{ $parameter->satuan }})
                        </label>
                        <input type="number" step="0.01" name="nilai_minimum" 
                               value="{{ old('nilai_minimum', $parameter->nilai_minimum) }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-sm font-mono bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Nilai Maksimum ({{ $parameter->satuan }})
                        </label>
                        <input type="number" step="0.01" name="nilai_maksimum" 
                               value="{{ old('nilai_maksimum', $parameter->nilai_maksimum) }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-sm font-mono bg-white">
                    </div>
                </div>
            </div>

            <!-- Grid 2: Batas Waspada -->
            <div class="p-5 rounded-2xl bg-amber-50/50 border border-amber-200/60 space-y-4">
                <span class="text-xs font-bold text-amber-900 uppercase tracking-wider block">
                    2. Ambang Batas Waspada (Toleransi Sebelum Tidak Layak)
                </span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Batas Waspada Minimum (&le; Nilai Min)
                        </label>
                        <input type="number" step="0.01" name="batas_waspada_min" 
                               value="{{ old('batas_waspada_min', $parameter->batas_waspada_min) }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-sm font-mono bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Batas Waspada Maksimum (&ge; Nilai Maks)
                        </label>
                        <input type="number" step="0.01" name="batas_waspada_maks" 
                               value="{{ old('batas_waspada_maks', $parameter->batas_waspada_maks) }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-sm font-mono bg-white">
                    </div>
                </div>
            </div>

            <!-- Tanggal Berlaku -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Tanggal Mulai Berlaku
                </label>
                <input type="date" name="tanggal_berlaku" 
                       min="{{ now()->toDateString() }}" 
                       value="{{ old('tanggal_berlaku', $parameter->tanggal_berlaku ? $parameter->tanggal_berlaku->toDateString() : now()->toDateString()) }}" required
                       class="w-full sm:w-1/2 px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 text-sm bg-white">
                <p class="text-[11px] text-slate-400 mt-1">Standar baru ini tidak berlaku surut untuk pengukuran di masa lalu.</p>
            </div>

            <!-- Form Actions -->
            <div class="pt-4 flex items-center gap-4">
                <button type="submit"
                        class="px-6 py-3 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-sm shadow-md shadow-cyan-600/20 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Versi Baru</span>
                </button>
                <a href="{{ route('baku-mutu.admin.index') }}"
                   class="px-5 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>

    </div>
</div>
@endsection
