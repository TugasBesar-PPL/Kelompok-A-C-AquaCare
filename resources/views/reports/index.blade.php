@extends('layouts.warga')

@section('title', 'Laporan Saya | AquaCare')
@section('breadcrumb', 'Laporan Saya')

@section('content')
<div class="mx-auto max-w-6xl">
    <section class="mb-6">
        <p class="text-xs font-bold uppercase tracking-widest text-cyan-600">
            Layanan Warga
        </p>

        <h1 class="mt-2 text-2xl font-extrabold text-slate-900 md:text-3xl">
            Laporan Saya
        </h1>

        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
            Lihat laporan masalah air yang pernah Anda ajukan.
        </p>
    </section>

    <section aria-label="Daftar laporan saya"
        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-8">

        <form class="mb-6 flex flex-col items-stretch gap-3 sm:flex-row sm:items-end"
              method="GET"
              action="{{ route('reports.index') }}">

            <div class="flex w-full flex-col gap-2 sm:max-w-sm">
                <label for="status" class="text-sm font-semibold text-slate-700">
                    Filter status laporan
                </label>

                <select
                    id="status"
                    name="status"
                    class="min-h-11 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-100"
                >
                    <option value="">Semua status</option>

                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected($selectedStatus === $status)>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button
                type="submit"
                class="rounded-xl bg-cyan-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-offset-2"
            >
                Terapkan Filter
            </button>
        </form>

        @if ($reports->total() === 0)
            <div class="rounded-xl border border-dashed border-slate-300 px-5 py-10 text-center">
                @if ($hasReports)
                    <h2 class="text-lg font-bold text-slate-800">
                        Tidak ada laporan dengan status ini
                    </h2>

                    <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">
                        Coba pilih status lain atau tampilkan semua laporan Anda.
                    </p>

                    <a
                        href="{{ route('reports.index') }}"
                        class="mt-5 inline-flex items-center justify-center rounded-xl bg-cyan-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-cyan-700"
                    >
                        Tampilkan Semua Laporan
                    </a>
                @else
                    <h2 class="text-lg font-bold text-slate-800">
                        Belum Ada Laporan
                    </h2>

                    <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">
                        Laporan yang Anda kirim akan muncul di halaman ini.
                    </p>

                    <a
                        href="{{ route('reports.create') }}"
                        class="mt-5 inline-flex items-center justify-center rounded-xl bg-cyan-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-cyan-700"
                    >
                        Ajukan Laporan
                    </a>
                @endif
            </div>

        @elseif ($reports->isEmpty())
            <div class="rounded-xl border border-dashed border-slate-300 px-5 py-10 text-center">
                <h2 class="text-lg font-bold text-slate-800">
                    Halaman Ini Tidak Berisi Laporan
                </h2>

                <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">
                    Tidak ada laporan pada halaman yang diminta. Kembali ke halaman pertama untuk melihat hasil.
                </p>

                <a
                    href="{{ route('reports.index', ['status' => $selectedStatus, 'page' => 1]) }}"
                    class="mt-5 inline-flex items-center justify-center rounded-xl bg-cyan-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-cyan-700"
                >
                    Kembali ke Halaman Pertama
                </a>
            </div>

        @else
            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full min-w-[700px] border-collapse text-left">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="whitespace-nowrap px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                ID Laporan
                            </th>
                            <th scope="col" class="whitespace-nowrap px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                Kategori Masalah
                            </th>
                            <th scope="col" class="whitespace-nowrap px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                Wilayah
                            </th>
                            <th scope="col" class="whitespace-nowrap px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                Status
                            </th>
                            <th scope="col" class="whitespace-nowrap px-5 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">
                                Tanggal Laporan
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach ($reports as $report)
                            <tr class="transition hover:bg-slate-50">
                                <td class="whitespace-nowrap px-5 py-4 text-sm font-bold text-cyan-700">
                                    <a
                                        href="{{ route('reports.show', $report->laporan_id) }}"
                                        class="hover:underline"
                                    >
                                        #{{ $report->laporan_id }}
                                    </a>
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-700">
                                    {{ $report->kategori_masalah }}
                                </td>

                                <td class="px-5 py-4 text-sm text-slate-700">
                                    {{ $report->wilayah }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex rounded-full bg-cyan-50 px-3 py-1 text-xs font-bold text-cyan-800">
                                        {{ $report->status_laporan }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                    {{ $report->tanggal_laporan->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($reports->hasPages())
                <div class="mt-6">
                    {{ $reports->links() }}
                </div>
            @endif
        @endif
    </section>
</div>
@endsection