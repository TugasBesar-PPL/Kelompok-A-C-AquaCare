@extends('layouts.app')

@section('title', 'AquaCare - Verifikasi & Penugasan Laporan (PBI-07)')
@section('breadcrumb', 'Verifikasi Laporan')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-cyan-100 text-cyan-800">
                    PBI-07 &bull; PIC: Fathir Aulia Helmi
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                    Use Case UC-11
                </span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Verifikasi & Penugasan Laporan Warga</h1>
            <p class="text-xs text-slate-500 mt-1 max-w-2xl">
                Sebagai admin, Anda dapat memeriksa validitas laporan permasalahan air dari warga, menerima lalu menugaskannya kepada petugas lapangan aktif, atau menolak laporan dengan mencantumkan alasan.
            </p>
        </div>

        <!-- Testing Tools / Info Box -->
        <div class="bg-cyan-50/70 border border-cyan-200 rounded-xl p-3.5 text-xs text-cyan-900 shrink-0 max-w-xs">
            <span class="font-bold flex items-center gap-1.5 text-cyan-800 mb-1">
                <i class="fa-solid fa-circle-info"></i> Ketentuan Validasi UC-11:
            </span>
            <ul class="list-disc list-inside text-[11px] space-y-0.5 text-cyan-800/90">
                <li><strong>Terima:</strong> Wajib pilih petugas yang berstatus <em>aktif</em>.</li>
                <li><strong>Tolak:</strong> Wajib menyertakan alasan penolakan.</li>
                <li><strong>Audit Log:</strong> Otomatis tercatat di sistem (PBI-17).</li>
            </ul>
        </div>
    </div>

    <!-- Filter Tabs & Search Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        
        <!-- Filter Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
            <a href="{{ route('reports.verification', ['status' => 'Menunggu']) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors whitespace-nowrap {{ $status === 'Menunggu' ? 'bg-amber-500 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Menunggu ({{ $counts['Menunggu'] }})
            </a>
            <a href="{{ route('reports.verification', ['status' => 'all']) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors whitespace-nowrap {{ $status === 'all' ? 'bg-slate-800 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Semua Laporan ({{ $counts['all'] }})
            </a>
            <a href="{{ route('reports.verification', ['status' => 'Ditugaskan']) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors whitespace-nowrap {{ $status === 'Ditugaskan' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Ditugaskan ({{ $counts['Ditugaskan'] }})
            </a>
            <a href="{{ route('reports.verification', ['status' => 'Diproses']) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors whitespace-nowrap {{ $status === 'Diproses' ? 'bg-cyan-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Diproses ({{ $counts['Diproses'] }})
            </a>
            <a href="{{ route('reports.verification', ['status' => 'Selesai']) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors whitespace-nowrap {{ $status === 'Selesai' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Selesai ({{ $counts['Selesai'] }})
            </a>
            <a href="{{ route('reports.verification', ['status' => 'Ditolak']) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors whitespace-nowrap {{ $status === 'Ditolak' ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Ditolak ({{ $counts['Ditolak'] }})
            </a>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('reports.verification') }}" class="w-full md:w-72 relative">
            <input type="hidden" name="status" value="{{ $status }}">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari ID, pelapor, lokasi..." 
                   class="w-full pl-8 pr-3 py-2 text-xs rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 bg-slate-50 focus:bg-white transition-colors">
        </form>
    </div>

    <!-- Main Table Laporan -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase font-semibold text-[11px]">
                        <th class="px-5 py-3.5">ID Laporan</th>
                        <th class="px-5 py-3.5">Waktu Lapor</th>
                        <th class="px-5 py-3.5">Pelapor</th>
                        <th class="px-5 py-3.5">Kategori & Masalah</th>
                        <th class="px-5 py-3.5">Wilayah & Alamat</th>
                        <th class="px-5 py-3.5 text-center">Bukti Foto</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Petugas / Penugasan</th>
                        <th class="px-5 py-3.5 text-center">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reports as $lap)
                    <tr class="hover:bg-slate-50/70 transition-colors {{ $lap->status_laporan === 'Menunggu' ? 'bg-amber-50/30' : '' }}">
                        <!-- ID Laporan -->
                        <td class="px-5 py-3.5 font-bold text-slate-900 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-800 border border-slate-200">
                                {{ $lap->kode_laporan }}
                            </span>
                        </td>

                        <!-- Tanggal Laporan -->
                        <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">
                            <span class="block font-medium text-slate-800">{{ $lap->tanggal_laporan ? $lap->tanggal_laporan->translatedFormat('d M Y') : '-' }}</span>
                            <span class="text-[10px] text-slate-400">{{ $lap->tanggal_laporan ? $lap->tanggal_laporan->format('H:i') . ' WIB' : '' }}</span>
                        </td>

                        <!-- Pelapor -->
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="block font-bold text-slate-800">{{ $lap->pelapor->name ?? 'Warga' }}</span>
                            <span class="text-[10px] text-slate-400">{{ $lap->pelapor->phone ?? $lap->pelapor->email ?? '-' }}</span>
                        </td>

                        <!-- Kategori & Masalah -->
                        <td class="px-5 py-3.5 max-w-xs">
                            <span class="font-bold text-slate-800 block text-xs">{{ $lap->kategori_masalah }}</span>
                            <p class="text-slate-500 text-[11px] truncate mt-0.5" title="{{ $lap->deskripsi }}">
                                {{ $lap->deskripsi }}
                            </p>
                        </td>

                        <!-- Wilayah & Alamat -->
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 mb-0.5">
                                {{ $lap->wilayah }}
                            </span>
                            <span class="block text-slate-600 text-[11px] truncate max-w-[140px]" title="{{ $lap->lokasi }}">{{ $lap->lokasi }}</span>
                        </td>

                        <!-- Foto Bukti -->
                        <td class="px-5 py-3.5 text-center">
                            @if($lap->foto_laporan)
                                <button onclick="showImageModal('{{ $lap->foto_laporan }}', '{{ $lap->kode_laporan }}')" class="relative group cursor-pointer inline-block">
                                    <img src="{{ $lap->foto_laporan }}" alt="Foto" class="w-10 h-10 object-cover rounded-md border border-slate-200 group-hover:opacity-80 transition-opacity">
                                    <span class="absolute inset-0 flex items-center justify-center bg-black/40 text-white rounded-md opacity-0 group-hover:opacity-100 transition-opacity text-[10px]">
                                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                                    </span>
                                </button>
                            @else
                                <span class="text-slate-400 text-[10px] italic">Tidak ada</span>
                            @endif
                        </td>

                        <!-- Status Badge -->
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($lap->status_laporan === 'Menunggu')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                    Menunggu
                                </span>
                            @elseif($lap->status_laporan === 'Ditugaskan')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                    Ditugaskan
                                </span>
                            @elseif($lap->status_laporan === 'Diproses')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full font-bold bg-cyan-100 text-cyan-800 border border-cyan-200">
                                    Diproses
                                </span>
                            @elseif($lap->status_laporan === 'Selesai')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Selesai
                                </span>
                            @elseif($lap->status_laporan === 'Ditolak')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                    Ditolak
                                </span>
                            @endif
                        </td>

                        <!-- Petugas / Penugasan -->
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($lap->petugas)
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-user-check text-cyan-600 text-xs"></i>
                                    <span class="font-bold text-slate-800">{{ $lap->petugas->name }}</span>
                                </div>
                                @if($lap->prioritas)
                                    <span class="inline-block mt-0.5 px-1.5 py-0.2 rounded text-[10px] font-semibold 
                                        {{ $lap->prioritas === 'Tinggi' ? 'bg-red-100 text-red-700' : ($lap->prioritas === 'Sedang' ? 'bg-orange-100 text-orange-700' : 'bg-emerald-100 text-emerald-700') }}">
                                        Prioritas: {{ $lap->prioritas }}
                                    </span>
                                @endif
                            @elseif($lap->status_laporan === 'Ditolak')
                                <span class="text-rose-600 font-medium text-[11px] truncate block max-w-[140px]" title="{{ $lap->catatan_verifikasi }}">
                                    Ditolak: {{ $lap->catatan_verifikasi }}
                                </span>
                            @else
                                <span class="text-slate-400 italic text-[11px]">Belum ditugaskan</span>
                            @endif
                        </td>

                        <!-- Aksi Verifikasi -->
                        <td class="px-5 py-3.5 text-center whitespace-nowrap">
                            @if($lap->status_laporan === 'Menunggu')
                                <button onclick="openVerificationModal({{ json_encode($lap) }})" 
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-lg shadow-xs transition-colors">
                                    <i class="fa-solid fa-clipboard-check"></i>
                                    <span>Verifikasi</span>
                                </button>
                            @else
                                <button onclick="openDetailModal({{ json_encode($lap) }})" 
                                        class="inline-flex items-center gap-1 px-3 py-1.5 border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold rounded-lg transition-colors">
                                    <i class="fa-solid fa-eye text-slate-400"></i>
                                    <span>Detail</span>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300"></i>
                            <p class="font-semibold">Tidak ada laporan dengan status ini.</p>
                            <p class="text-[11px] text-slate-400">Pilih tab status lain atau ubah kata kunci pencarian.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
                    <p class="text-xs text-slate-300">PBI-07: Verifikasi dan Penugasan Laporan (UC-11)</p>
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
                        Pilih Keputusan Admin <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center justify-center gap-2 p-3.5 border-2 rounded-xl cursor-pointer transition-all border-cyan-500 bg-cyan-50/50" id="radioLabelTerima">
                            <input type="radio" name="keputusan" value="diterima" checked class="text-cyan-600 focus:ring-cyan-500" onchange="toggleDecisionForm('diterima')">
                            <span class="text-xs font-bold text-slate-800">
                                <i class="fa-solid fa-check text-emerald-600 mr-1"></i> Terima & Tugaskan
                            </span>
                        </label>

                        <label class="relative flex items-center justify-center gap-2 p-3.5 border-2 rounded-xl cursor-pointer transition-all border-slate-200 hover:border-slate-300" id="radioLabelTolak">
                            <input type="radio" name="keputusan" value="ditolak" class="text-rose-600 focus:ring-rose-500" onchange="toggleDecisionForm('ditolak')">
                            <span class="text-xs font-bold text-slate-800">
                                <i class="fa-solid fa-ban text-rose-600 mr-1"></i> Tolak Laporan
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Bagian 1: Form Penugasan Petugas (Jika Diterima) -->
                <div id="sectionTerima" class="space-y-4 pt-2 border-t border-slate-100">
                    
                    <!-- Pilih Petugas Lapangan (UC-11 Alur Alternatif A2) -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-xs font-bold text-slate-700">
                                Pilih Petugas Lapangan <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] text-cyan-700 font-medium">Hanya petugas aktif yang valid</span>
                        </div>
                        <select name="officer_id" id="officerSelect" class="w-full px-3.5 py-2.5 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 bg-white">
                            <option value="">-- Pilih Petugas Lapangan --</option>
                            <optgroup label="Petugas Aktif (Tersedia)">
                                @foreach($petugasAktif as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->phone ?? $p->email }}) - Aktif</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Petugas Nonaktif (Uji Validasi A2)">
                                @foreach($semuaPetugas->where('is_active', false) as $pi)
                                    <option value="{{ $pi->id }}">{{ $pi->name }} (Nonaktif - Menolak otomatis)</option>
                                @endforeach
                            </optgroup>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">
                            Tip pengujian: Memilih petugas nonaktif akan memicu pesan penolakan sesuai <em>UC-11 Alur Alternatif A2</em>.
                        </p>
                    </div>

                    <!-- Tingkat Prioritas (PBI-16 Integration) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Tingkat Prioritas Penanganan
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="flex items-center justify-center gap-1.5 p-2 rounded-lg border border-slate-200 text-xs font-semibold cursor-pointer hover:bg-slate-50">
                                <input type="radio" name="prioritas" value="Tinggi" class="text-rose-600 focus:ring-rose-500">
                                <span>Tinggi</span>
                            </label>
                            <label class="flex items-center justify-center gap-1.5 p-2 rounded-lg border border-slate-200 text-xs font-semibold cursor-pointer hover:bg-slate-50">
                                <input type="radio" name="prioritas" value="Sedang" checked class="text-orange-600 focus:ring-orange-500">
                                <span>Sedang</span>
                            </label>
                            <label class="flex items-center justify-center gap-1.5 p-2 rounded-lg border border-slate-200 text-xs font-semibold cursor-pointer hover:bg-slate-50">
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
                        Sesuai skenario <strong>UC-11 Alur Alternatif A1</strong>, admin wajib mengisi alasan penolakan, status laporan menjadi Ditolak.
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">
                            Alasan Penolakan <span class="text-rose-500">* (Wajib Diisi)</span>
                        </label>
                        <textarea name="alasan_penolakan" id="alasanPenolakanInput" rows="3" placeholder="Jelaskan alasan penolakan kepada warga, contoh: Lokasi di luar jangkauan wilayah distribusi / Data pelapor tidak valid / Laporan duplikat." class="w-full p-3 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
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
            <h3 class="font-bold text-base text-white">Detail Informasi Laporan</h3>
            <button onclick="closeDetailModal()" class="text-slate-400 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-6 space-y-4 text-xs">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <span class="text-base font-extrabold text-slate-800" id="detailKode">-</span>
                    <span class="text-slate-400 block text-[11px]" id="detailPelapor">-</span>
                </div>
                <span id="detailStatusBadge" class="px-2.5 py-1 rounded-full font-bold text-xs">-</span>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-slate-400 block">Kategori:</span>
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
                <span class="text-slate-400 block mb-1">Deskripsi Masalah:</span>
                <div class="p-3 bg-slate-50 rounded-lg text-slate-700 leading-relaxed" id="detailDeskripsi">-</div>
            </div>
            <div id="detailCatatanWrapper" class="hidden">
                <span class="text-slate-400 block mb-1" id="detailCatatanLabel">Catatan Verifikasi / Alasan:</span>
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

<!-- ========================================== -->
<!-- MODAL PREVIEW FOTO                         -->
<!-- ========================================== -->
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="relative max-w-2xl w-full bg-slate-900 rounded-2xl overflow-hidden shadow-2xl">
        <div class="p-4 flex items-center justify-between text-white border-b border-slate-800">
            <span class="text-xs font-bold" id="imageModalTitle">Foto Bukti</span>
            <button onclick="closeImageModal()" class="text-slate-400 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <div class="p-2 flex items-center justify-center bg-black/40">
            <img id="imageModalImg" src="" alt="Bukti Laporan" class="max-h-[70vh] object-contain rounded-lg">
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
        const labelTerima = document.getElementById('radioLabelTerima');
        const labelTolak = document.getElementById('radioLabelTolak');

        if (keputusan === 'diterima') {
            secTerima.classList.remove('hidden');
            secTolak.classList.add('hidden');
            officerSelect.setAttribute('required', 'required');
            alasanInput.removeAttribute('required');

            labelTerima.className = "relative flex items-center justify-center gap-2 p-3.5 border-2 rounded-xl cursor-pointer transition-all border-cyan-500 bg-cyan-50/50";
            labelTolak.className = "relative flex items-center justify-center gap-2 p-3.5 border-2 rounded-xl cursor-pointer transition-all border-slate-200 hover:border-slate-300";

            btnSubmit.className = "px-5 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors flex items-center gap-2";
            btnText.innerText = "Tugaskan ke Petugas";
        } else {
            secTerima.classList.add('hidden');
            secTolak.classList.remove('hidden');
            officerSelect.removeAttribute('required');
            alasanInput.setAttribute('required', 'required');

            labelTerima.className = "relative flex items-center justify-center gap-2 p-3.5 border-2 rounded-xl cursor-pointer transition-all border-slate-200 hover:border-slate-300";
            labelTolak.className = "relative flex items-center justify-center gap-2 p-3.5 border-2 rounded-xl cursor-pointer transition-all border-rose-500 bg-rose-50/50";

            btnSubmit.className = "px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors flex items-center gap-2";
            btnText.innerText = "Tolak Laporan Warga";
        }
    }

    function openDetailModal(lap) {
        document.getElementById('detailKode').innerText = lap.kode_laporan;
        document.getElementById('detailPelapor').innerText = `Pelapor: ${lap.pelapor ? lap.pelapor.name : 'Warga'}`;
        document.getElementById('detailKategori').innerText = lap.kategori_masalah;
        document.getElementById('detailLokasi').innerText = `${lap.wilayah} - ${lap.lokasi}`;
        document.getElementById('detailPetugas').innerText = lap.petugas ? `${lap.petugas.name}` : '-';
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

    function showImageModal(src, title) {
        document.getElementById('imageModalImg').src = src;
        document.getElementById('imageModalTitle').innerText = `Foto Bukti: ${title}`;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }
</script>
@endsection

