@extends('layouts.aquacare')

@section('title', 'Input Hasil Pengukuran — AquaCare')
@section('breadcrumb', 'Pengukuran / Input Hasil Pengukuran (PBI-03)')

@section('content')
<div class="max-w-2xl bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
    <h1 class="text-xl font-bold text-slate-900 mb-1">Input Hasil Pengukuran Kualitas Air</h1>
    <p class="text-sm text-slate-500 mb-6">Catat hasil pengukuran lima parameter pada titik sumber air.</p>

    <form action="{{ route('monitoring.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Titik Sumber Air *</label>
            <select name="titik_air_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                <option value="">-- Pilih Titik Sumber Air --</option>
                @foreach ($titikList as $titik)
                    <option value="{{ $titik->titik_air_id }}"
                        {{ (string) old('titik_air_id', $selectedTitikId) === (string) $titik->titik_air_id ? 'selected' : '' }}>
                        {{ $titik->kode_titik }} — {{ $titik->nama }} ({{ $titik->wilayah }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal & Jam Pengukuran *</label>
                <input type="datetime-local" name="tanggal_pengukuran" required
                       value="{{ old('tanggal_pengukuran', now()->format('Y-m-d\TH:i')) }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">ID Jadwal Pemeriksaan (Opsional)</label>
                <input type="number" name="jadwal_id" value="{{ old('jadwal_id') }}" placeholder="Kosongkan jika insidental"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>
        </div>

        <div class="border-t border-slate-200 pt-4">
            <h2 class="text-sm font-semibold text-slate-800 mb-3">Parameter Kualitas Air</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">pH (0 - 14) *</label>
                    <input type="number" step="0.01" min="0" max="14" name="ph" required value="{{ old('ph') }}"
                           placeholder="Contoh: 7.1"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kekeruhan (NTU) *</label>
                    <input type="number" step="0.01" min="0" name="kekeruhan" required value="{{ old('kekeruhan') }}"
                           placeholder="Contoh: 3.8"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">TDS (mg/L) *</label>
                    <input type="number" step="0.01" min="0" name="tds" required value="{{ old('tds') }}"
                           placeholder="Contoh: 245"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Suhu (°C) *</label>
                    <input type="number" step="0.01" min="0" name="suhu" required value="{{ old('suhu') }}"
                           placeholder="Contoh: 26.5"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Sisa Klor (mg/L) *</label>
                    <input type="number" step="0.01" min="0" name="sisa_klor" required value="{{ old('sisa_klor') }}"
                           placeholder="Contoh: 0.4"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
            </div>
        </div>

        <div class="pt-4 flex items-center gap-3">
            <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-[#0f3443] text-white text-sm font-semibold hover:bg-[#164a5f]">
                Simpan Pengukuran
            </button>
            <a href="{{ route('monitoring.index') }}"
               class="px-4 py-2.5 rounded-lg border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection