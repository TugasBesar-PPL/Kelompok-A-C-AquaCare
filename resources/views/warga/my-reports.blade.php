@extends('layouts.app')

@section('title', 'Daftar Laporan Saya - AquaCare')
@section('breadcrumb-parent', 'WARGA')
@section('breadcrumb-title', 'Daftar Laporan Saya')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Action Banner -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
        <div>
            <span class="text-[11px] font-bold uppercase tracking-wider text-cyan-600 block mb-1">
                STATUS & RIWAYAT
            </span>
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">
                Riwayat Pelaporan Masalah Air
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Pantau proses verifikasi dan tindak lanjut laporan air bersih di lingkungan Anda.
            </p>
        </div>

        <a href="{{ route('warga.lapor') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs shadow-md shadow-cyan-600/20 transition-all shrink-0">
            <i class="fa-solid fa-plus"></i>
            <span>Buat Laporan Baru</span>
        </a>
    </div>

    <!-- Reports Table / List -->
    <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-600 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-6">Kode</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Wilayah / Alamat</th>
                        <th class="py-3.5 px-4">Tanggal Masuk</th>
                        <th class="py-3.5 px-4">Prioritas</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($reports as $report)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6 font-bold text-cyan-700">
                                {{ $report->kode_laporan }}
                            </td>
                            <td class="py-4 px-4 text-slate-900 font-semibold">
                                {{ $report->kategori_masalah }}
                            </td>
                            <td class="py-4 px-4 text-slate-600">
                                <span class="font-semibold text-slate-800 block">{{ $report->wilayah }}</span>
                                <span class="text-[11px] text-slate-400 truncate max-w-xs block">{{ $report->lokasi }}</span>
                            </td>
                            <td class="py-4 px-4 text-slate-500">
                                {{ $report->tanggal_laporan ? $report->tanggal_laporan->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-4 px-4">
                                @if($report->prioritas === 'Tinggi')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                        Tinggi
                                    </span>
                                @elseif($report->prioritas === 'Sedang')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                        Sedang
                                    </span>
                                @elseif($report->prioritas === 'Rendah')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        Rendah
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[11px]">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @php
                                    $badgeClasses = match($report->status_laporan) {
                                        'Menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'Ditugaskan' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'Diproses' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                        'Selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'Ditolak' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-slate-100 text-slate-600 border-slate-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badgeClasses }}">
                                    {{ $report->status_laporan }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-inbox text-3xl mb-2 block text-slate-300"></i>
                                <p class="text-sm font-semibold">Belum ada laporan yang diajukan.</p>
                                <p class="text-xs text-slate-400 mt-0.5">Ajukan laporan pertama Anda jika mengalami masalah kualitas atau aliran air.</p>
                                <a href="{{ route('warga.lapor') }}" class="inline-block mt-4 px-4 py-2 rounded-lg bg-cyan-600 text-white text-xs font-bold hover:bg-cyan-700">
                                    Ajukan Sekarang
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                {{ $reports->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
