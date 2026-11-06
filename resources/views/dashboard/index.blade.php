@extends('layouts.app')

@section('title', 'AquaCare - Dashboard Admin')
@section('breadcrumb', 'Ringkasan')

@section('content')
<div class="space-y-6">

    <!-- Header Greeting & Quick Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Dashboard Ringkasan Operasional</h1>
            <p class="text-sm text-slate-500 mt-0.5">Pemantauan titik kualitas air dan verifikasi laporan warga terpadu.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('reports.verification') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                <i class="fa-solid fa-clipboard-check"></i>
                <span>Kelola Verifikasi Laporan</span>
                <span class="bg-cyan-800 text-cyan-200 text-xs px-2 py-0.5 rounded-full font-bold ml-1">{{ $laporanMasuk }}</span>
            </a>
        </div>
    </div>

    <!-- Ringkasan Statistik Kartu (Persis Sesuai Mockup Gambar 8) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Titik Layak -->
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Titik Layak</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $totalLayak }}</span>
                <span class="w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-emerald-50"></span>
            </div>
            <span class="text-[11px] text-emerald-600 font-medium mt-1">Kualitas Sesuai Baku Mutu</span>
        </div>

        <!-- Titik Waspada -->
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Titik Waspada</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $totalWaspada }}</span>
                <span class="w-3 h-3 rounded-full bg-amber-500 ring-4 ring-amber-50"></span>
            </div>
            <span class="text-[11px] text-amber-600 font-medium mt-1">Perlu Pemantauan Rutin</span>
        </div>

        <!-- Titik Tidak Layak -->
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Titik Tidak Layak</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $totalTidakLayak }}</span>
                <span class="w-3 h-3 rounded-full bg-rose-500 ring-4 ring-rose-50"></span>
            </div>
            <span class="text-[11px] text-rose-600 font-medium mt-1">Tindakan Cepat Diperlukan</span>
        </div>

        <!-- Lap. Masuk (Menunggu Verifikasi) -->
        <div class="bg-white rounded-xl p-4 border border-cyan-200 bg-gradient-to-br from-cyan-50/50 to-white shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-cyan-800 uppercase tracking-wider">Lap. Masuk</span>
                <span class="text-[10px] bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded font-bold">PBI-07</span>
            </div>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-cyan-900">{{ $laporanMasuk }}</span>
                <i class="fa-solid fa-inbox text-cyan-500 text-xl"></i>
            </div>
            <span class="text-[11px] text-amber-600 font-semibold mt-1">Menunggu Verifikasi</span>
        </div>

        <!-- Diproses -->
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Diproses</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $laporanDiproses }}</span>
                <i class="fa-solid fa-spinner text-blue-500 text-xl"></i>
            </div>
            <span class="text-[11px] text-blue-600 font-medium mt-1">Dalam Penanganan Petugas</span>
        </div>

        <!-- Selesai -->
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Selesai</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-extrabold text-slate-800">{{ $laporanSelesai }}</span>
                <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
            </div>
            <span class="text-[11px] text-emerald-600 font-medium mt-1">Laporan Tertangani</span>
        </div>
    </div>

    <!-- Main Content Grid (Sesuai Mockup Gambar 8) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Kolom Kiri: Tabel Laporan Warga Terbaru (8 Cols) -->
        <div class="lg:col-span-8 bg-white rounded-xl border border-slate-200 shadow-xs flex flex-col">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-slate-800 text-base">Laporan Warga Terbaru</h2>
                    <p class="text-xs text-slate-500">Daftar laporan warga yang masuk dan memerlukan tindakan verifikasi atau peninjauan.</p>
                </div>
                <a href="{{ route('reports.verification') }}" class="text-xs font-semibold text-cyan-600 hover:text-cyan-700 flex items-center gap-1">
                    <span>Lihat Semua</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            </div>

            <!-- Tabel Sesuai Mockup Gambar 8: ID | Tanggal | Kategori | Wilayah | Status | Aksi -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase font-semibold">
                            <th class="px-5 py-3.5">ID</th>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Kategori</th>
                            <th class="px-5 py-3.5">Wilayah</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-normal">
                        @forelse($laporanTerbaru as $lap)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3 font-semibold text-slate-900">
                                {{ $lap->kode_laporan }}
                            </td>
                            <td class="px-5 py-3 text-slate-600 whitespace-nowrap">
                                {{ $lap->tanggal_laporan ? $lap->tanggal_laporan->translatedFormat('d M') : '-' }}
                            </td>
                            <td class="px-5 py-3 text-slate-800">
                                {{ $lap->kategori_masalah }}
                            </td>
                            <td class="px-5 py-3 text-slate-600">
                                {{ $lap->wilayah }}
                            </td>
                            <td class="px-5 py-3">
                                @if($lap->status_laporan === 'Menunggu')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        Menunggu
                                    </span>
                                @elseif($lap->status_laporan === 'Ditugaskan')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                        Ditugaskan
                                    </span>
                                @elseif($lap->status_laporan === 'Diproses')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-cyan-100 text-cyan-800 border border-cyan-200">
                                        Diproses
                                    </span>
                                @elseif($lap->status_laporan === 'Selesai')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Selesai
                                    </span>
                                @elseif($lap->status_laporan === 'Ditolak')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center whitespace-nowrap">
                                @if($lap->status_laporan === 'Menunggu')
                                    <!-- Tombol Verifikasi Menonjol (Cyan Sesuai Mockup Gambar 8) -->
                                    <button onclick="openVerificationModal({{ json_encode($lap) }})" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold text-xs rounded-md shadow-xs transition-colors">
                                        <i class="fa-solid fa-clipboard-check text-[11px]"></i>
                                        <span>Verifikasi</span>
                                    </button>
                                @else
                                    <!-- Tombol Detail (Outline Sesuai Mockup Gambar 8) -->
                                    <button onclick="openDetailModal({{ json_encode($lap) }})" 
                                            class="inline-flex items-center px-3 py-1.5 border border-slate-300 hover:bg-slate-100 text-slate-700 font-medium text-xs rounded-md transition-colors">
                                        Detail
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                                Belum ada laporan warga yang tercatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Kolom Kanan: Sebaran Status Titik & Pengukuran Terbaru (4 Cols) -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Card: Sebaran Status Titik (16) (Mockup Gambar 8) -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-sm">Sebaran Status Titik ({{ $totalTitik }})</h3>
                    <span class="text-xs text-slate-400">Kondisi Terkini</span>
                </div>

                <!-- Stacked Progress Bar (Persis Mockup Gambar 8) -->
                <div class="mt-4">
                    <div class="w-full h-4 bg-slate-100 rounded-full overflow-hidden flex shadow-inner">
                        <div style="width: 75%;" class="bg-emerald-500 h-full" title="Layak: 75%"></div>
                        <div style="width: 19%;" class="bg-amber-400 h-full" title="Waspada: 19%"></div>
                        <div style="width: 6%;" class="bg-rose-500 h-full" title="Tidak Layak: 6%"></div>
                    </div>

                    <!-- Legend -->
                    <div class="flex items-center justify-between mt-3 text-xs text-slate-600 font-medium">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <span>Layak 75%</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                            <span>Waspada 19%</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                            <span>Tidak Layak 6%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Pengukuran Terbaru (Mockup Gambar 8) -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-sm">Pengukuran Terbaru</h3>
                    <span class="text-xs text-cyan-600 font-semibold cursor-pointer">PBI-03</span>
                </div>

                <!-- Table Pengukuran Terbaru (Mockup Gambar 8) -->
                <div class="mt-2 divide-y divide-slate-100">
                    <div class="grid grid-cols-12 py-2 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                        <div class="col-span-6">Titik</div>
                        <div class="col-span-3 text-center">pH</div>
                        <div class="col-span-3 text-right">Status</div>
                    </div>

                    @foreach($pengukuranTerbaru as $ukur)
                    <div class="grid grid-cols-12 py-2.5 items-center text-xs">
                        <div class="col-span-6 font-medium text-slate-800 truncate" title="{{ $ukur->titikAir->nama ?? '-' }}">
                            <span class="font-bold text-slate-900">{{ $ukur->titikAir->kode_titik ?? '' }}</span> 
                            <span class="text-slate-600">{{ Str::limit($ukur->titikAir->nama ?? '-', 14) }}</span>
                        </div>
                        <div class="col-span-3 text-center font-bold text-slate-700">
                            {{ number_format($ukur->ph, 1) }}
                        </div>
                        <div class="col-span-3 text-right">
                            @if($ukur->status_kelayakan === 'Layak')
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-100 text-emerald-800">Layak</span>
                            @elseif($ukur->status_kelayakan === 'Waspada')
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-100 text-amber-800">Waspada</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-100 text-rose-800">Tidak Layak</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

</div>

<!-- ========================================== -->
<!-- MODAL VERIFIKASI LAPORAN (PBI-07 & UC-11) -->
<!-- ========================================== -->
<div id="verificationModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl border border-slate-200 overflow-hidden my-8 transform transition-all">
        
        <!-- Header Modal -->
        <div class="bg-navy-900 text-white px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <div>
                    <h3 class="font-bold text-base text-white">Verifikasi Laporan Masalah Air</h3>
                    <p class="text-xs text-slate-300">PBI-07: Validasi laporan dan penugasan tindak lanjut ke petugas.</p>
                </div>
            </div>
            <button onclick="closeVerificationModal()" class="text-slate-400 hover:text-white transition-colors p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="verificationForm" method="POST" action="">
            @csrf
            @method('PATCH')

            <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">

                <!-- Detail Laporan Warga -->
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold px-2 py-0.5 bg-cyan-100 text-cyan-800 rounded" id="modalKodeLaporan">LP-XXXX</span>
                            <span class="text-xs text-slate-500" id="modalTanggalLaporan">-</span>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800">Menunggu Verifikasi</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div>
                            <span class="text-slate-500 block">Kategori Masalah:</span>
                            <span class="font-semibold text-slate-800 text-sm" id="modalKategori">-</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block">Wilayah / Lokasi:</span>
                            <span class="font-semibold text-slate-800" id="modalWilayahLokasi">-</span>
                        </div>
                    </div>

                    <div>
                        <span class="text-xs text-slate-500 block">Deskripsi Laporan Warga:</span>
                        <p class="text-xs text-slate-700 bg-white p-3 rounded-lg border border-slate-200 mt-1 leading-relaxed" id="modalDeskripsi">
                            -
                        </p>
                    </div>

                    <!-- Foto Bukti dari Pelapor -->
                    <div id="modalFotoWrapper" class="pt-1">
                        <span class="text-xs text-slate-500 block mb-1">Foto Bukti Pelapor:</span>
                        <div class="relative rounded-lg overflow-hidden border border-slate-200 bg-slate-100 max-h-48 flex items-center justify-center">
                            <img id="modalFotoImg" src="" alt="Bukti Foto" class="object-cover w-full h-44 cursor-pointer hover:opacity-90 transition-opacity" onclick="window.open(this.src, '_blank')">
                        </div>
                    </div>
                </div>

                <!-- Pilihan Keputusan: Terima atau Tolak (UC-11) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                        Keputusan Verifikasi Admin <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center justify-center gap-2 p-3.5 border-2 rounded-xl cursor-pointer transition-all border-cyan-500 bg-cyan-50/50 has-checked:border-cyan-600 has-checked:bg-cyan-50" id="radioLabelTerima">
                            <input type="radio" name="keputusan" value="diterima" checked class="text-cyan-600 focus:ring-cyan-500" onchange="toggleDecisionForm('diterima')">
                            <span class="text-xs font-bold text-slate-800">
                                <i class="fa-solid fa-check text-emerald-600 mr-1"></i> Terima & Tugaskan
                            </span>
                        </label>

                        <label class="relative flex items-center justify-center gap-2 p-3.5 border-2 rounded-xl cursor-pointer transition-all border-slate-200 hover:border-slate-300 has-checked:border-rose-600 has-checked:bg-rose-50" id="radioLabelTolak">
                            <input type="radio" name="keputusan" value="ditolak" class="text-rose-600 focus:ring-rose-500" onchange="toggleDecisionForm('ditolak')">
                            <span class="text-xs font-bold text-slate-800">
                                <i class="fa-solid fa-ban text-rose-600 mr-1"></i> Tolak Laporan
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Bagian 1: Form Penugasan Petugas (Jika Diterima) -->
                <div id="sectionTerima" class="space-y-4 pt-2 border-t border-slate-100">
                    
                    <!-- Pilih Petugas Lapangan Aktif (UC-11) -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-xs font-bold text-slate-700">
                                Pilih Petugas Lapangan <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-slate-400">Hanya petugas aktif yang dapat ditugaskan</span>
                        </div>
                        <select name="officer_id" id="officerSelect" class="w-full px-3.5 py-2.5 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 bg-white">
                            <option value="">-- Pilih Petugas yang Bertugas --</option>
                            @foreach($petugasAktif as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->phone ?? $p->email }}) - Aktif</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tingkat Prioritas (PBI-16 Integration) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tingkat Prioritas Penanganan
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="flex items-center justify-center gap-1.5 p-2 rounded-lg border border-slate-200 text-xs font-semibold cursor-pointer hover:bg-slate-50 has-checked:border-rose-500 has-checked:bg-rose-50 has-checked:text-rose-700">
                                <input type="radio" name="prioritas" value="Tinggi" class="text-rose-600 focus:ring-rose-500">
                                <span>Tinggi</span>
                            </label>
                            <label class="flex items-center justify-center gap-1.5 p-2 rounded-lg border border-slate-200 text-xs font-semibold cursor-pointer hover:bg-slate-50 has-checked:border-orange-500 has-checked:bg-orange-50 has-checked:text-orange-700">
                                <input type="radio" name="prioritas" value="Sedang" checked class="text-orange-600 focus:ring-orange-500">
                                <span>Sedang</span>
                            </label>
                            <label class="flex items-center justify-center gap-1.5 p-2 rounded-lg border border-slate-200 text-xs font-semibold cursor-pointer hover:bg-slate-50 has-checked:border-emerald-500 has-checked:bg-emerald-50 has-checked:text-emerald-700">
                                <input type="radio" name="prioritas" value="Rendah" class="text-emerald-600 focus:ring-emerald-500">
                                <span>Rendah</span>
                            </label>
                        </div>
                    </div>

                    <!-- Catatan Verifikasi untuk Petugas -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Catatan / Instruksi Tindak Lanjut untuk Petugas (Opsional)
                        </label>
                        <textarea name="catatan" rows="2" placeholder="Contoh: Bawa alat cek turbidity meter dan bersihkan filter pipa distribusi..." class="w-full p-3 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500"></textarea>
                    </div>
                </div>

                <!-- Bagian 2: Form Alasan Penolakan (Jika Ditolak - UC-11 Alur Alternatif A1) -->
                <div id="sectionTolak" class="hidden space-y-4 pt-2 border-t border-slate-100">
                    <div class="p-3 bg-rose-50 rounded-lg border border-rose-200 text-xs text-rose-800">
                        <i class="fa-solid fa-triangle-exclamation mr-1 text-rose-600"></i>
                        Sesuai skenario <strong>UC-11 Alur Alternatif A1</strong>, admin wajib mencantumkan alasan penolakan yang jelas agar pelapor mendapatkan penjelasan transparan.
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Alasan Penolakan <span class="text-rose-500">* (Wajib Diisi)</span>
                        </label>
                        <textarea name="alasan_penolakan" id="alasanPenolakanInput" rows="3" placeholder="Jelaskan alasan penolakan, misalnya: Laporan duplikat / Titik air bukan wilayah operasional AquaCare / Informasi lokasi tidak lengkap." class="w-full p-3 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
                    </div>
                </div>

            </div>

            <!-- Footer Modal Action -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                <button type="button" onclick="closeVerificationModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 transition-colors">
                    Batal
                </button>
                <button type="submit" id="btnSubmitVerifikasi" class="px-5 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span id="btnSubmitText">Tugaskan ke Petugas</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL DETAIL LAPORAN                       -->
<!-- ========================================== -->
<div id="detailModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl border border-slate-200 overflow-hidden my-8">
        <div class="bg-navy-900 text-white px-6 py-4 flex items-center justify-between">
            <h3 class="font-bold text-base text-white">Detail Laporan Warga</h3>
            <button onclick="closeDetailModal()" class="text-slate-400 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-6 space-y-4 text-xs">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <span class="text-base font-extrabold text-slate-800" id="detailKode">-</span>
                <span id="detailStatusBadge" class="px-2.5 py-1 rounded-full font-bold text-xs">-</span>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-slate-400 block">Kategori Masalah:</span>
                    <span class="font-semibold text-slate-800" id="detailKategori">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block">Wilayah / Lokasi:</span>
                    <span class="font-semibold text-slate-800" id="detailLokasi">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block">Petugas Ditugaskan:</span>
                    <span class="font-semibold text-slate-800" id="detailPetugas">-</span>
                </div>
                <div>
                    <span class="text-slate-400 block">Prioritas:</span>
                    <span class="font-semibold text-slate-800" id="detailPrioritas">-</span>
                </div>
            </div>
            <div>
                <span class="text-slate-400 block mb-1">Deskripsi:</span>
                <div class="p-3 bg-slate-50 rounded-lg text-slate-700" id="detailDeskripsi">-</div>
            </div>
            <div id="detailCatatanWrapper" class="hidden">
                <span class="text-slate-400 block mb-1" id="detailCatatanLabel">Catatan Verifikasi:</span>
                <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-amber-900" id="detailCatatan">-</div>
            </div>
        </div>
        <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 text-right">
            <button onclick="closeDetailModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-lg">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openVerificationModal(lap) {
        const form = document.getElementById('verificationForm');
        form.action = `/reports/${lap.laporan_id}/verification`;

        document.getElementById('modalKodeLaporan').innerText = lap.kode_laporan;
        document.getElementById('modalTanggalLaporan').innerText = new Date(lap.tanggal_laporan).toLocaleDateString('id-ID', {
            day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
        });
        document.getElementById('modalKategori').innerText = lap.kategori_masalah;
        document.getElementById('modalWilayahLokasi').innerText = `${lap.wilayah} - ${lap.lokasi}`;
        document.getElementById('modalDeskripsi').innerText = lap.deskripsi;

        const fotoWrapper = document.getElementById('modalFotoWrapper');
        const fotoImg = document.getElementById('modalFotoImg');
        if (lap.foto_laporan) {
            fotoImg.src = lap.foto_laporan;
            fotoWrapper.classList.remove('hidden');
        } else {
            fotoWrapper.classList.add('hidden');
        }

        // Reset to default Terima
        document.querySelector('input[name="keputusan"][value="diterima"]').checked = true;
        toggleDecisionForm('diterima');

        document.getElementById('verificationModal').classList.remove('hidden');
    }

    function closeVerificationModal() {
        document.getElementById('verificationModal').classList.add('hidden');
    }

    function toggleDecisionForm(keputusan) {
        const secTerima = document.getElementById('sectionTerima');
        const secTolak = document.getElementById('sectionTolak');
        const officerSelect = document.getElementById('officerSelect');
        const alasanInput = document.getElementById('alasanPenolakanInput');
        const btnSubmit = document.getElementById('btnSubmitVerifikasi');
        const btnText = document.getElementById('btnSubmitText');

        if (keputusan === 'diterima') {
            secTerima.classList.remove('hidden');
            secTolak.classList.add('hidden');
            officerSelect.setAttribute('required', 'required');
            alasanInput.removeAttribute('required');

            btnSubmit.className = "px-5 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors flex items-center gap-2";
            btnText.innerText = "Tugaskan ke Petugas";
        } else {
            secTerima.classList.add('hidden');
            secTolak.classList.remove('hidden');
            officerSelect.removeAttribute('required');
            alasanInput.setAttribute('required', 'required');

            btnSubmit.className = "px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors flex items-center gap-2";
            btnText.innerText = "Tolak Laporan Warga";
        }
    }

    function openDetailModal(lap) {
        document.getElementById('detailKode').innerText = lap.kode_laporan;
        document.getElementById('detailKategori').innerText = lap.kategori_masalah;
        document.getElementById('detailLokasi').innerText = `${lap.wilayah} - ${lap.lokasi}`;
        document.getElementById('detailPetugas').innerText = lap.petugas ? lap.petugas.name : (lap.petugas_id ? `Petugas #${lap.petugas_id}` : '-');
        document.getElementById('detailPrioritas').innerText = lap.prioritas || '-';
        document.getElementById('detailDeskripsi').innerText = lap.deskripsi;

        const badge = document.getElementById('detailStatusBadge');
        badge.innerText = lap.status_laporan;
        badge.className = "px-2.5 py-1 rounded-full font-bold text-xs " + (
            lap.status_laporan === 'Menunggu' ? 'bg-amber-100 text-amber-800' :
            lap.status_laporan === 'Ditugaskan' ? 'bg-blue-100 text-blue-800' :
            lap.status_laporan === 'Diproses' ? 'bg-cyan-100 text-cyan-800' :
            lap.status_laporan === 'Selesai' ? 'bg-emerald-100 text-emerald-800' :
            'bg-rose-100 text-rose-800'
        );

        const catatanWrap = document.getElementById('detailCatatanWrapper');
        if (lap.catatan_verifikasi) {
            catatanWrap.classList.remove('hidden');
            document.getElementById('detailCatatan').innerText = lap.catatan_verifikasi;
            document.getElementById('detailCatatanLabel').innerText = lap.status_laporan === 'Ditolak' ? 'Alasan Penolakan:' : 'Catatan Verifikasi:';
        } else {
            catatanWrap.classList.add('hidden');
        }

        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
</script>
@endsection

