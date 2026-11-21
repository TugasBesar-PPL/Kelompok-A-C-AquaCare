@extends('layouts.app')

@section('title', "Detail Laporan {$report->kode_laporan} - AquaCare")
@section('breadcrumb', "Detail {$report->kode_laporan}")

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Action / Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('reports.verification') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-600 hover:text-cyan-700 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali ke Verifikasi Laporan</span>
        </a>
        <div class="flex items-center gap-2">
            @if($report->status_laporan === 'Menunggu')
            <button onclick="window.location.href='{{ route('reports.verification') }}'" class="inline-flex items-center gap-1.5 px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs rounded-lg shadow-sm transition-colors">
                <i class="fa-solid fa-clipboard-check"></i>
                <span>Verifikasi Laporan Ini</span>
            </button>
            @endif
        </div>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        
        <!-- Header Banner -->
        <div class="bg-slate-900 text-white px-8 py-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-black tracking-tight text-white">{{ $report->kode_laporan }}</h1>
                    <span class="px-3 py-1 rounded-full text-xs font-bold border 
                        {{ $report->status_laporan === 'Menunggu' ? 'bg-amber-500/20 text-amber-300 border-amber-500/40' : 
                          ($report->status_laporan === 'Ditugaskan' ? 'bg-blue-500/20 text-blue-300 border-blue-500/40' : 
                          ($report->status_laporan === 'Diproses' ? 'bg-cyan-500/20 text-cyan-300 border-cyan-500/40' : 
                          ($report->status_laporan === 'Selesai' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40' : 
                          'bg-rose-500/20 text-rose-300 border-rose-500/40'))) }}">
                        {{ $report->status_laporan }}
                    </span>
                    @if($report->prioritas)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold 
                        {{ $report->prioritas === 'Tinggi' ? 'bg-red-500 text-white' : ($report->prioritas === 'Sedang' ? 'bg-orange-500 text-white' : 'bg-emerald-500 text-white') }}">
                        Prioritas: {{ $report->prioritas }}
                    </span>
                    @endif
                </div>
                <p class="text-xs text-slate-300 mt-1">
                    Dilaporkan pada {{ $report->tanggal_laporan ? $report->tanggal_laporan->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}
                </p>
            </div>

            <div class="text-right text-xs">
                <span class="text-slate-400 block">Kategori Masalah</span>
                <span class="text-base font-bold text-cyan-400">{{ $report->kategori_masalah }}</span>
            </div>
        </div>

        <!-- Status Progression Stepper -->
        <div class="p-6 bg-slate-50 border-b border-slate-200">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-4">Alur Penanganan Laporan:</span>
            <div class="flex items-center justify-between max-w-3xl mx-auto">
                
                <!-- Step 1: Menunggu -->
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs bg-cyan-600 text-white ring-4 ring-cyan-100">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 mt-1.5">Laporan Masuk</span>
                    <span class="text-[10px] text-slate-500">Menunggu</span>
                </div>

                <div class="flex-1 h-1 mx-2 {{ in_array($report->status_laporan, ['Ditugaskan', 'Diproses', 'Selesai']) ? 'bg-cyan-600' : 'bg-slate-200' }}"></div>

                <!-- Step 2: Diverifikasi & Ditugaskan -->
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs 
                        {{ in_array($report->status_laporan, ['Ditugaskan', 'Diproses', 'Selesai']) ? 'bg-cyan-600 text-white ring-4 ring-cyan-100' : ($report->status_laporan === 'Ditolak' ? 'bg-rose-600 text-white ring-4 ring-rose-100' : 'bg-slate-200 text-slate-500') }}">
                        @if($report->status_laporan === 'Ditolak')
                            <i class="fa-solid fa-xmark"></i>
                        @elseif(in_array($report->status_laporan, ['Ditugaskan', 'Diproses', 'Selesai']))
                            <i class="fa-solid fa-check"></i>
                        @else
                            2
                        @endif
                    </div>
                    <span class="text-xs font-bold text-slate-800 mt-1.5">Verifikasi Admin</span>
                    <span class="text-[10px] text-slate-500">{{ $report->status_laporan === 'Ditolak' ? 'Ditolak' : 'Ditugaskan (PBI-07)' }}</span>
                </div>

                <div class="flex-1 h-1 mx-2 {{ in_array($report->status_laporan, ['Diproses', 'Selesai']) ? 'bg-cyan-600' : 'bg-slate-200' }}"></div>

                <!-- Step 3: Diproses -->
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs 
                        {{ in_array($report->status_laporan, ['Diproses', 'Selesai']) ? 'bg-cyan-600 text-white ring-4 ring-cyan-100' : 'bg-slate-200 text-slate-500' }}">
                        @if(in_array($report->status_laporan, ['Diproses', 'Selesai']))
                            <i class="fa-solid fa-check"></i>
                        @else
                            3
                        @endif
                    </div>
                    <span class="text-xs font-bold text-slate-800 mt-1.5">Penanganan</span>
                    <span class="text-[10px] text-slate-500">Diproses Petugas</span>
                </div>

                <div class="flex-1 h-1 mx-2 {{ $report->status_laporan === 'Selesai' ? 'bg-emerald-600' : 'bg-slate-200' }}"></div>

                <!-- Step 4: Selesai -->
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs 
                        {{ $report->status_laporan === 'Selesai' ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : 'bg-slate-200 text-slate-500' }}">
                        @if($report->status_laporan === 'Selesai')
                            <i class="fa-solid fa-check-double"></i>
                        @else
                            4
                        @endif
                    </div>
                    <span class="text-xs font-bold text-slate-800 mt-1.5">Selesai</span>
                    <span class="text-[10px] text-slate-500">Tuntas</span>
                </div>

            </div>
        </div>

        <!-- Body Content -->
        <div class="p-8 grid grid-cols-1 md:grid-cols-12 gap-8 text-xs">
            
            <!-- Left Info (7 Cols) -->
            <div class="md:col-span-7 space-y-6">
                
                <!-- Detail Pelapor & Lokasi -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Informasi Pelapor & Lokasi</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-slate-400 block">Nama Warga:</span>
                            <span class="font-bold text-slate-800 text-sm">{{ $report->pelapor->name ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Kontak / Telepon:</span>
                            <span class="font-bold text-slate-800">{{ $report->pelapor->phone ?? $report->pelapor->email ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Wilayah:</span>
                            <span class="font-bold text-slate-800">{{ $report->wilayah }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Alamat / Titik:</span>
                            <span class="font-bold text-slate-800">{{ $report->lokasi }}</span>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi Masalah -->
                <div class="space-y-2">
                    <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Deskripsi Permasalahan</h3>
                    <p class="text-slate-700 bg-slate-50 p-4 rounded-xl border border-slate-200 leading-relaxed text-sm">
                        {{ $report->deskripsi }}
                    </p>
                </div>

                <!-- Hasil Verifikasi & Penugasan (PBI-07) -->
                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Hasil Verifikasi Admin (PBI-07)</h3>
                    
                    @if($report->adminVerifikator || $report->status_laporan !== 'Menunggu')
                        <div class="bg-cyan-50/50 rounded-xl p-4 border border-cyan-200 space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <span class="text-slate-500 block">Admin Verifikator:</span>
                                    <span class="font-bold text-cyan-900">{{ $report->adminVerifikator->name ?? 'Fathir Aulia Helmi (Admin)' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Waktu Verifikasi / Penugasan:</span>
                                    <span class="font-semibold text-slate-700">
                                        {{ $report->tanggal_ditugaskan ? $report->tanggal_ditugaskan->translatedFormat('d M Y, H:i') . ' WIB' : ($report->updated_at ? $report->updated_at->translatedFormat('d M Y, H:i') . ' WIB' : '-') }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Petugas Ditugaskan:</span>
                                    <span class="font-bold text-slate-800">
                                        {{ $report->petugas ? $report->petugas->name . ' (' . ($report->petugas->phone ?? 'Petugas') . ')' : '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block">Prioritas Penanganan:</span>
                                    <span class="font-bold text-slate-800">{{ $report->prioritas ?? '-' }}</span>
                                </div>
                            </div>

                            @if($report->catatan_verifikasi)
                            <div class="pt-2 border-t border-cyan-100">
                                <span class="text-slate-500 block text-[11px] mb-1">
                                    {{ $report->status_laporan === 'Ditolak' ? 'Alasan Penolakan Laporan:' : 'Catatan / Instruksi Tindak Lanjut:' }}
                                </span>
                                <p class="p-3 bg-white rounded-lg border border-cyan-200 text-slate-800 font-medium">
                                    {{ $report->catatan_verifikasi }}
                                </p>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900">
                            <p class="font-semibold">Laporan ini masih berstatus <strong>Menunggu Verifikasi</strong>.</p>
                            <p class="text-xs text-amber-700 mt-0.5">Admin belum menerima atau menolak laporan ini.</p>
                        </div>
                    @endif
                </div>

                <!-- Riwayat Penanganan Lapangan (PBI-08) -->
                @if($report->riwayatPenanganan->count() > 0)
                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Riwayat Tindak Lanjut Petugas</h3>
                    <div class="space-y-2">
                        @foreach($report->riwayatPenanganan as $riwayat)
                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                            <div class="flex items-center justify-between text-[11px] text-slate-500 mb-1">
                                <span class="font-bold text-slate-800">{{ $riwayat->petugas->name ?? 'Petugas' }} ({{ $riwayat->status_penanganan }})</span>
                                <span>{{ $riwayat->tanggal_pembaruan ? $riwayat->tanggal_pembaruan->translatedFormat('d M Y, H:i') : '' }}</span>
                            </div>
                            <p class="text-slate-700">{{ $riwayat->catatan }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            <!-- Right Info: Foto Laporan (5 Cols) -->
            <div class="md:col-span-5 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-2">Foto Pendukung Pelapor</h3>
                @if($report->foto_laporan)
                    <div class="rounded-xl overflow-hidden border border-slate-200 shadow-xs bg-slate-100">
                        <img src="{{ $report->foto_laporan }}" alt="Foto Bukti" class="w-full h-64 object-cover">
                        <div class="p-3 bg-slate-50 text-slate-500 text-[11px] flex justify-between items-center">
                            <span>Foto bukti dari warga</span>
                            <a href="{{ $report->foto_laporan }}" target="_blank" class="text-cyan-600 hover:underline font-semibold">
                                Buka Ukuran Penuh &rarr;
                            </a>
                        </div>
                    </div>
                @else
                    <div class="h-48 rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 bg-slate-50">
                        <i class="fa-regular fa-image text-3xl mb-2 text-slate-300"></i>
                        <span>Pelapor tidak melampirkan foto</span>
                    </div>
                @endif

                <!-- Titik Sumber Air Terkait -->
                @if($report->titikAir)
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 space-y-2">
                    <span class="text-slate-400 block text-[11px] uppercase font-bold">Titik Sumber Air Terkait</span>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-cyan-600 text-white flex items-center justify-center font-bold text-xs">
                            <i class="fa-solid fa-faucet-drip"></i>
                        </div>
                        <div>
                            <span class="font-bold text-slate-800 block text-xs">{{ $report->titikAir->nama }}</span>
                            <span class="text-[11px] text-slate-500">{{ $report->titikAir->kode_titik }} &bull; {{ $report->titikAir->jenis }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection

