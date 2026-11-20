@extends('layouts.aquacare')

@section('title', $titik->nama . ' — Detail Monitoring AquaCare')
@section('breadcrumb')
    <a href="{{ route('monitoring.index') }}" class="hover:underline">Titik Sumber Air</a> /
    <span class="font-semibold text-slate-800">{{ $titik->kode_titik }} {{ $titik->nama }}</span>
@endsection

@section('content')
@php
    $headerBadge = match($titik->status_terkini) {
        'Layak' => 'bg-emerald-100 text-emerald-800',
        'Waspada' => 'bg-amber-100 text-amber-800',
        default => 'bg-rose-100 text-rose-800',
    };
@endphp

<!-- Header sesuai Mockup Gambar 7 -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-slate-900">{{ $titik->nama }}</h1>
            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $headerBadge }}">
                {{ $titik->status_terkini }}
            </span>
        </div>
        <p class="text-sm text-slate-500 mt-1">
            {{ $titik->wilayah }} ·
            @if ($latest)
                Pengukuran terakhir {{ $latest->tanggal_pengukuran->format('d M Y, H:i') }}
            @else
                Belum ada pengukuran
            @endif
        </p>
    </div>

    <div class="flex items-center gap-2">
        <a href="{{ route('monitoring.create', ['titik_air_id' => $titik->titik_air_id]) }}"
           class="px-4 py-2 rounded-lg bg-[#0f3443] text-white text-sm font-semibold hover:bg-[#164a5f]">
            + Input Pengukuran
        </a>
    </div>
</div>

<!-- 5 Kartu Parameter Terakhir (Mockup Gambar 7) -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
    @foreach ($standards as $kode => $meta)
        @php
            $val = $latest ? $latest->{$kode} : '-';
            $st = $paramStatuses[$kode] ?? 'Layak';
            $borderClass = $st === 'Waspada' ? 'border-amber-400' : ($st === 'Tidak Layak' ? 'border-rose-400' : 'border-slate-200');
            $pillClass = $st === 'Waspada' ? 'bg-amber-100 text-amber-800' : ($st === 'Tidak Layak' ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800');
        @endphp
        <div class="bg-white rounded-xl border {{ $borderClass }} p-4 shadow-sm">
            <div class="text-xs font-medium text-slate-500">{{ $meta['label'] }}</div>
            <div class="mt-1 flex items-baseline gap-1">
                <span class="text-2xl font-bold text-slate-900">{{ $val }}</span>
                @if ($meta['unit'])
                    <span class="text-xs text-slate-500">{{ $meta['unit'] }}</span>
                @endif
            </div>
            <span class="mt-2 inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $pillClass }}">
                {{ $latest ? $st : 'Belum ada data' }}
            </span>
        </div>
    @endforeach
</div>

<!-- Filter Rentang Tanggal (PBI-05 UC-08) -->
<form method="GET" action="{{ route('monitoring.show', $titik->titik_air_id) }}"
      class="bg-white rounded-xl border border-slate-200 p-4 mb-6 flex flex-wrap items-end gap-4 shadow-sm">
    <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">Dari Tanggal</label>
        <input type="date" name="from" value="{{ $from }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm">
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">Sampai Tanggal</label>
        <input type="date" name="to" value="{{ $to }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm">
    </div>
    <button type="submit" class="px-4 py-1.5 rounded-lg bg-teal-700 text-white text-sm font-medium hover:bg-teal-800">
        Terapkan Filter
    </button>
    @if ($from || $to)
        <a href="{{ route('monitoring.show', $titik->titik_air_id) }}"
           class="px-3 py-1.5 rounded-lg border border-slate-300 text-sm text-slate-600 hover:bg-slate-50">
            Reset
        </a>
    @endif
</form>

<!-- Ringkasan Nilai Minimum, Maksimum, dan Rata-rata (PBI-05) -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
        <div class="text-xs text-slate-500">Nilai Minimum (<span id="statParamLabel1">Kekeruhan</span>)</div>
        <div class="text-xl font-bold text-slate-900 mt-1" id="statMin">{{ $ringkasan['kekeruhan']['min'] }}</div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
        <div class="text-xs text-slate-500">Nilai Maksimum (<span id="statParamLabel2">Kekeruhan</span>)</div>
        <div class="text-xl font-bold text-slate-900 mt-1" id="statMax">{{ $ringkasan['kekeruhan']['max'] }}</div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
        <div class="text-xs text-slate-500">Rata-rata (<span id="statParamLabel3">Kekeruhan</span>)</div>
        <div class="text-xl font-bold text-slate-900 mt-1" id="statAvg">{{ $ringkasan['kekeruhan']['avg'] }}</div>
    </div>
</div>

<!-- Grafik Tren & Tabel Riwayat Pengukuran (2 Kolom sesuai Mockup Gambar 7) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Grafik Tren (Kiri) -->
    <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <h2 class="text-base font-bold text-slate-900" id="chartTitle">Tren Kekeruhan</h2>
            <div class="flex flex-wrap gap-1.5" id="paramTabs">
                @foreach ($standards as $kode => $meta)
                    <button type="button" data-param="{{ $kode }}"
                            class="param-btn px-3 py-1 rounded-lg text-xs font-semibold border {{ $kode === 'kekeruhan' ? 'bg-[#0f3443] text-white border-[#0f3443]' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' }}">
                        {{ $meta['label'] }}
                    </button>
                @endforeach
            </div>
        </div>

        @if ($riwayatDesc->isEmpty())
            <div class="py-16 text-center text-sm text-slate-500">
                Tidak ada pengukuran pada rentang tanggal tersebut.
            </div>
        @else
            <div class="h-72">
                <canvas id="trendChart"></canvas>
            </div>
        @endif
    </div>

    <!-- Riwayat Pengukuran (Kanan) -->
    <div class="lg:col-span-5 bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <h2 class="text-base font-bold text-slate-900 mb-4">Riwayat Pengukuran</h2>

        @if ($riwayatDesc->isEmpty())
            <div class="py-16 text-center text-sm text-slate-500">
                Belum ada riwayat pengukuran.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-2 pr-2">Tanggal</th>
                            <th class="py-2 px-2">pH</th>
                            <th class="py-2 px-2">Keruh</th>
                            <th class="py-2 px-2">TDS</th>
                            <th class="py-2 pl-2">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($riwayatDesc as $row)
                            @php
                                $rowBadge = match($row->status_kelayakan) {
                                    'Layak' => 'bg-emerald-100 text-emerald-800',
                                    'Waspada' => 'bg-amber-100 text-amber-800',
                                    default => 'bg-rose-100 text-rose-800',
                                };
                            @endphp
                            <tr>
                                <td class="py-2.5 pr-2 whitespace-nowrap">{{ $row->tanggal_pengukuran->format('d M') }}</td>
                                <td class="py-2.5 px-2">{{ $row->ph }}</td>
                                <td class="py-2.5 px-2">{{ $row->kekeruhan }}</td>
                                <td class="py-2.5 px-2">{{ $row->tds }}</td>
                                <td class="py-2.5 pl-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $rowBadge }}">
                                        {{ $row->status_kelayakan }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@if ($riwayatDesc->isNotEmpty())
<script>
const labels = {{ \Illuminate\Support\Js::from($chartLabels) }};
const seriesData = {{ \Illuminate\Support\Js::from($chartSeries) }};
const standardsMeta = {{ \Illuminate\Support\Js::from($standards) }};
const summaryData = {{ \Illuminate\Support\Js::from($ringkasan) }};

    const ctx = document.getElementById('trendChart').getContext('2d');

    function buildConfig(paramKey) {
        const meta = standardsMeta[paramKey];
        const limitMax = meta.max;
        const unit = meta.unit ? ` ${meta.unit}` : '';

        return {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: `${meta.label}${unit ? ' (' + meta.unit + ')' : ''}`,
                    data: seriesData[paramKey],
                    borderColor: '#0f766e',
                    backgroundColor: 'rgba(15, 118, 110, 0.15)',
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: '#0f766e',
                    tension: 0.2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    annotation: {
                        annotations: {
                            bakuMutuLine: {
                                type: 'line',
                                yMin: limitMax,
                                yMax: limitMax,
                                borderColor: '#991b1b',
                                borderWidth: 1.5,
                                borderDash: [5, 5],
                                label: {
                                    display: true,
                                    content: `Batas baku mutu ${limitMax}${unit}`,
                                    position: 'end',
                                    backgroundColor: 'transparent',
                                    color: '#991b1b',
                                    font: { size: 11, weight: 'bold' }
                                }
                            }
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        };
    }

    let trendChart = new Chart(ctx, buildConfig('kekeruhan'));

    document.querySelectorAll('.param-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const paramKey = btn.dataset.param;
            const meta = standardsMeta[paramKey];

            document.querySelectorAll('.param-btn').forEach(b => {
                b.className = 'param-btn px-3 py-1 rounded-lg text-xs font-semibold border bg-white text-slate-700 border-slate-300 hover:bg-slate-50';
            });
            btn.className = 'param-btn px-3 py-1 rounded-lg text-xs font-semibold border bg-[#0f3443] text-white border-[#0f3443]';

            document.getElementById('chartTitle').textContent = `Tren ${meta.label}`;
            document.getElementById('statParamLabel1').textContent = meta.label;
            document.getElementById('statParamLabel2').textContent = meta.label;
            document.getElementById('statParamLabel3').textContent = meta.label;
            document.getElementById('statMin').textContent = summaryData[paramKey].min;
            document.getElementById('statMax').textContent = summaryData[paramKey].max;
            document.getElementById('statAvg').textContent = summaryData[paramKey].avg;

            trendChart.destroy();
            trendChart = new Chart(ctx, buildConfig(paramKey));
        });
    });
</script>
@endif
@endsection