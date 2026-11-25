@extends('layouts.warga')

@section('title', 'Detail Laporan #' . $report->laporan_id . ' | AquaCare')
@section('breadcrumb', 'Detail Laporan')

@section('content')
<div class="mx-auto max-w-6xl">
    <a href="{{ route('reports.index') }}"
       class="mb-5 inline-flex items-center gap-2 text-sm font-semibold text-cyan-700 hover:underline">
        &larr; Kembali ke laporan saya
    </a>

    <section class="mb-6">
        <p class="text-xs font-bold uppercase tracking-widest text-cyan-600">
            Pelacakan Laporan
        </p>

        <h1 class="mt-2 text-2xl font-extrabold text-slate-900 md:text-3xl">
            Detail Laporan #{{ $report->laporan_id }}
        </h1>

        <p class="mt-3 text-sm leading-6 text-slate-600">
            Informasi laporan masalah air dan riwayat penanganannya.
        </p>
    </section>

    <section class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-8">
        <h2 class="mb-6 text-lg font-bold text-slate-900">
            Informasi Laporan
        </h2>

        <dl class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <dt class="mb-1 text-xs font-bold uppercase tracking-wider text-slate-500">
                    ID Laporan
                </dt>
                <dd class="text-sm font-semibold text-slate-800">
                    #{{ $report->laporan_id }}
                </dd>
            </div>

            <div>
                <dt class="mb-1 text-xs font-bold uppercase tracking-wider text-slate-500">
                    Status Laporan
                </dt>
                <dd>
                    <span class="inline-flex rounded-full bg-cyan-50 px-3 py-1 text-xs font-bold text-cyan-800">
                        {{ $report->status_laporan }}
                    </span>
                </dd>
            </div>

            <div>
                <dt class="mb-1 text-xs font-bold uppercase tracking-wider text-slate-500">
                    Kategori Masalah
                </dt>
                <dd class="text-sm text-slate-800">
                    {{ $report->kategori_masalah }}
                </dd>
            </div>

            <div>
                <dt class="mb-1 text-xs font-bold uppercase tracking-wider text-slate-500">
                    Wilayah
                </dt>
                <dd class="text-sm text-slate-800">
                    {{ $report->wilayah }}
                </dd>
            </div>

            <div>
                <dt class="mb-1 text-xs font-bold uppercase tracking-wider text-slate-500">
                    Latitude
                </dt>
                <dd class="text-sm text-slate-800">
                    {{ $report->lokasi['latitude'] ?? '—' }}
                </dd>
            </div>

            <div>
                <dt class="mb-1 text-xs font-bold uppercase tracking-wider text-slate-500">
                    Longitude
                </dt>
                <dd class="text-sm text-slate-800">
                    {{ $report->lokasi['longitude'] ?? '—' }}
                </dd>
            </div>

            <div class="sm:col-span-2">
                <dt class="mb-1 text-xs font-bold uppercase tracking-wider text-slate-500">
                    Alamat Kejadian
                </dt>
                <dd class="text-sm leading-6 text-slate-800">
                    {{ $report->lokasi['alamat'] ?? '—' }}
                </dd>
            </div>

            <div class="sm:col-span-2">
                <dt class="mb-1 text-xs font-bold uppercase tracking-wider text-slate-500">
                    Deskripsi Masalah
                </dt>
                <dd class="whitespace-pre-line text-sm leading-6 text-slate-800">
                    {{ $report->deskripsi }}
                </dd>
            </div>

            <div>
                <dt class="mb-1 text-xs font-bold uppercase tracking-wider text-slate-500">
                    Tanggal Laporan
                </dt>
                <dd class="text-sm text-slate-800">
                    {{ $report->tanggal_laporan->format('d/m/Y H:i') }}
                </dd>
            </div>

            <div class="sm:col-span-2">
                <dt class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-500">
                    Foto Laporan
                </dt>
                <dd>
                    <img
                        class="max-h-[420px] w-full max-w-xl rounded-xl border border-slate-200 bg-slate-50 object-contain"
                        src="{{ route('reports.photo', $report->laporan_id) }}"
                        alt="Foto laporan #{{ $report->laporan_id }}"
                        loading="lazy"
                    >
                </dd>
            </div>
        </dl>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-8">
        <h2 class="mb-6 text-lg font-bold text-slate-900">
            Riwayat Penanganan
        </h2>

        @if (! $historyAvailable)
            <div class="rounded-xl border border-dashed border-slate-300 px-5 py-8 text-center">
                <p class="text-sm text-slate-500">
                    Riwayat penanganan belum tersedia.
                </p>
            </div>
        @elseif ($history->isEmpty())
            <div class="rounded-xl border border-dashed border-slate-300 px-5 py-8 text-center">
                <p class="text-sm text-slate-500">
                    Laporan ini belum memiliki riwayat penanganan.
                </p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($history as $item)
                    <article class="rounded-xl border border-slate-200 p-5">
                        <h3 class="font-bold text-slate-800">
                            {{ $item->status_penanganan }}
                        </h3>

                        <p class="mt-1 text-xs text-slate-500">
                            {{ \Illuminate\Support\Carbon::parse($item->tanggal_pembaruan)->format('d/m/Y H:i') }}
                        </p>

                        <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-700">
                            {{ $item->catatan ?: 'Tidak ada catatan.' }}
                        </p>

                        @if ($item->foto_bukti)
                            <img
                                class="mt-4 max-h-[420px] w-full max-w-xl rounded-xl border border-slate-200 bg-slate-50 object-contain"
                                src="{{ route('reports.history-photo', ['id' => $report->laporan_id, 'penangananId' => $item->penanganan_id]) }}"
                                alt="Foto bukti riwayat {{ $item->status_penanganan }}"
                                loading="lazy"
                            >
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection