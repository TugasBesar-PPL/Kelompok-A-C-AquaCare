@extends('layouts.aquacare')

@section('title', 'Daftar Titik Sumber Air — AquaCare')
@section('breadcrumb', 'Titik Sumber Air / Daftar Monitoring')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Monitoring Kualitas Air</h1>
        <p class="text-sm text-slate-500">Pilih titik sumber air untuk melihat riwayat & grafik tren (PBI-05) atau input pengukuran baru (PBI-03).</p>
    </div>
    <a href="{{ route('monitoring.create') }}"
       class="px-4 py-2.5 rounded-lg bg-[#0f3443] text-white text-sm font-semibold hover:bg-[#164a5f]">
        + Input Pengukuran
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse ($titikList as $titik)
        @php
            $badge = match($titik->status_terkini) {
                'Layak' => 'bg-emerald-100 text-emerald-800',
                'Waspada' => 'bg-amber-100 text-amber-800',
                default => 'bg-rose-100 text-rose-800',
            };
        @endphp
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between gap-2 mb-2">
                    <span class="text-xs font-semibold text-slate-500">{{ $titik->kode_titik }}</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">
                        {{ $titik->status_terkini }}
                    </span>
                </div>
                <h2 class="text-lg font-bold text-slate-900">{{ $titik->nama }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $titik->wilayah }} · {{ $titik->jenis }}</p>
                <p class="text-xs text-slate-400 mt-2">
                    Pengukuran terakhir: {{ $titik->pengukuran_terakhir ? $titik->pengukuran_terakhir->format('d M Y, H:i') : 'Belum ada data' }}
                </p>
            </div>
            <div class="mt-5 flex gap-2">
                <a href="{{ route('monitoring.show', $titik->titik_air_id) }}"
                   class="flex-1 text-center px-3 py-2 rounded-lg border border-slate-300 text-sm font-medium hover:bg-slate-50">
                    Lihat Tren & Riwayat
                </a>
                <a href="{{ route('monitoring.create', ['titik_air_id' => $titik->titik_air_id]) }}"
                   class="px-3 py-2 rounded-lg bg-teal-700 text-white text-sm font-medium hover:bg-teal-800">
                    + Ukur
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-xl border border-slate-200 p-8 text-center text-slate-500">
            Belum ada titik sumber air. Jalankan seeder terlebih dahulu.
        </div>
    @endforelse
</div>
@endsection