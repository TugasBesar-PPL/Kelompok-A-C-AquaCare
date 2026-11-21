@extends('layouts.app')

@section('title', 'AquaCare - Penjadwalan Pemeriksaan Rutin (PBI-09)')
@section('breadcrumb', 'Jadwal Pemeriksaan')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-cyan-100 text-cyan-800">
                    PBI-09 &bull; PIC: Fathir Aulia Helmi
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                    Use Case UC-13 & UC-14
                </span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Penjadwalan Pemeriksaan Rutin</h1>
            <p class="text-xs text-slate-500 mt-1 max-w-2xl">
                Sebagai admin, Anda dapat menjadwalkan pemeriksaan berkala per titik dan petugas tanpa jadwal yang bentrok. Sebagai petugas, Anda dapat memantau tugas pemeriksaan Anda dan menandainya selesai.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs rounded-lg shadow-sm transition-colors">
                <i class="fa-solid fa-calendar-plus text-sm"></i>
                <span>+ Jadwal Baru</span>
            </button>
        </div>
    </div>

    <!-- Bentrok Alert Banner if Session has collision error -->
    @if($errors->has('bentrok'))
    <div class="p-4 rounded-xl bg-rose-50 border-2 border-rose-300 text-rose-900 shadow-sm flex items-start gap-3 animate-pulse">
        <i class="fa-solid fa-triangle-exclamation text-rose-600 text-xl mt-0.5"></i>
        <div>
            <p class="font-bold text-sm">Peringatan Bentrok Jadwal (UC-13 Alur Alternatif A1):</p>
            <p class="text-xs mt-1 text-rose-800">{{ $errors->first('bentrok') }}</p>
        </div>
    </div>
    @endif

    <!-- Information Card: Collision Rule (UC-13) -->
    <div class="bg-gradient-to-r from-cyan-50 to-blue-50 border border-cyan-200 p-4 rounded-xl flex items-center justify-between gap-4 text-xs text-slate-700">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-cyan-600 text-white flex items-center justify-center shrink-0">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <span class="font-bold text-slate-800">Sistem Deteksi Bentrok Otomatis (Collision Prevention):</span>
                <p class="text-[11px] text-slate-600 mt-0.5">
                    Sistem secara otomatis mencegah bentrok jika waktu penugasan beririsan dengan jadwal aktif lain milik petugas yang sama atau titik air yang sama.
                </p>
            </div>
        </div>
        <div class="text-[11px] font-semibold text-cyan-800 bg-white/80 px-3 py-1.5 rounded-lg border border-cyan-200 shrink-0">
            Status login: <span class="text-slate-900 font-bold">{{ $user->name }} ({{ $user->role }})</span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        
        <!-- Status Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
            <a href="{{ route('inspections.index', ['status' => 'dijadwalkan']) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors whitespace-nowrap {{ request('status', 'dijadwalkan') === 'dijadwalkan' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Dijadwalkan ({{ $counts['dijadwalkan'] }})
            </a>
            <a href="{{ route('inspections.index', ['status' => 'selesai']) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors whitespace-nowrap {{ request('status') === 'selesai' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Selesai ({{ $counts['selesai'] }})
            </a>
            <a href="{{ route('inspections.index', ['status' => 'dibatalkan']) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors whitespace-nowrap {{ request('status') === 'dibatalkan' ? 'bg-rose-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Dibatalkan ({{ $counts['dibatalkan'] }})
            </a>
            <a href="{{ route('inspections.index', ['status' => 'all']) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors whitespace-nowrap {{ request('status') === 'all' ? 'bg-slate-800 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Semua ({{ $counts['all'] }})
            </a>
        </div>

        <!-- Filter Dropdowns -->
        <form method="GET" action="{{ route('inspections.index') }}" class="flex items-center gap-2 w-full md:w-auto">
            <input type="hidden" name="status" value="{{ request('status', 'dijadwalkan') }}">
            
            <select name="water_point_id" onchange="this.form.submit()" class="text-xs px-2.5 py-1.5 rounded-lg border border-slate-200 bg-slate-50 focus:outline-none focus:ring-1 focus:ring-cyan-500">
                <option value="">Semua Titik Air</option>
                @foreach($titikAirList as $t)
                    <option value="{{ $t->titik_air_id }}" {{ request('water_point_id') == $t->titik_air_id ? 'selected' : '' }}>{{ $t->kode_titik }} - {{ Str::limit($t->nama, 18) }}</option>
                @endforeach
            </select>

            @if(!$isPetugasOnly)
            <select name="officer_id" onchange="this.form.submit()" class="text-xs px-2.5 py-1.5 rounded-lg border border-slate-200 bg-slate-50 focus:outline-none focus:ring-1 focus:ring-cyan-500">
                <option value="">Semua Petugas</option>
                @foreach($semuaPetugas as $p)
                    <option value="{{ $p->id }}" {{ request('officer_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
            @endif
        </form>
    </div>

    <!-- Main Table: Jadwal Pemeriksaan -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase font-semibold text-[11px]">
                        <th class="px-5 py-3.5">ID</th>
                        <th class="px-5 py-3.5">Titik Sumber Air</th>
                        <th class="px-5 py-3.5">Petugas Lapangan</th>
                        <th class="px-5 py-3.5">Waktu Pemeriksaan</th>
                        <th class="px-5 py-3.5 text-center">Durasi</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Catatan</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inspections as $item)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-5 py-3.5 font-bold text-slate-900">
                            #JD-{{ str_pad($item->jadwal_id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="font-bold text-slate-800 block text-xs">{{ $item->titikAir->nama ?? '-' }}</span>
                            <span class="text-slate-400 text-[10px]">{{ $item->titikAir->kode_titik ?? '' }} &bull; {{ $item->titikAir->wilayah ?? '' }}</span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <i class="fa-solid fa-user-check text-cyan-600 text-xs"></i>
                                <span class="font-bold text-slate-800">{{ $item->petugas->name ?? '-' }}</span>
                            </div>
                            <span class="text-[10px] text-slate-400 block">{{ $item->petugas->phone ?? $item->petugas->email ?? '' }}</span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            <span class="font-bold text-slate-800 block">
                                {{ $item->tanggal_jadwal->translatedFormat('d M Y') }}
                            </span>
                            <span class="text-cyan-700 text-[11px] font-semibold">
                                {{ $item->tanggal_jadwal->format('H:i') }} - {{ $item->waktu_selesai->format('H:i') }} WIB
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-semibold text-[11px]">
                                {{ $item->durasi_menit }} menit
                            </span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($item->status_pemeriksaan === 'dijadwalkan')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                    Dijadwalkan
                                </span>
                            @elseif($item->status_pemeriksaan === 'selesai')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    <i class="fa-solid fa-check mr-1 text-[10px]"></i> Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                    Dibatalkan
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 max-w-xs text-slate-500 truncate" title="{{ $item->catatan }}">
                            {{ $item->catatan ?: '-' }}
                        </td>
                        <td class="px-5 py-3.5 text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                @if($item->status_pemeriksaan === 'dijadwalkan')
                                    <!-- Aksi Tandai Selesai (UC-14) -->
                                    <form method="POST" action="{{ route('inspections.status', $item->jadwal_id) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="selesai">
                                        <button type="submit" onclick="return confirm('Tandai pemeriksaan di {{ $item->titikAir->nama }} telah selesai?')" 
                                                class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-md shadow-xs transition-colors text-[11px] inline-flex items-center gap-1" 
                                                title="Tandai Selesai (UC-14)">
                                            <i class="fa-solid fa-check"></i>
                                            <span>Selesai</span>
                                        </button>
                                    </form>

                                    @if(!$isPetugasOnly)
                                        <!-- Aksi Ubah / Pindahkan Jadwal (UC-13 A3) -->
                                        <button onclick="openEditModal({{ json_encode($item) }})" 
                                                class="px-2.5 py-1.5 border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold rounded-md transition-colors text-[11px]" 
                                                title="Pindahkan / Ubah Jadwal">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <!-- Aksi Batalkan Jadwal -->
                                        <form method="POST" action="{{ route('inspections.status', $item->jadwal_id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="dibatalkan">
                                            <button type="submit" onclick="return confirm('Batalkan jadwal pemeriksaan ini?')" 
                                                    class="px-2.5 py-1.5 border border-rose-200 text-rose-600 hover:bg-rose-50 font-semibold rounded-md transition-colors text-[11px]" 
                                                    title="Batalkan">
                                                <i class="fa-solid fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-slate-400 text-[11px] italic">Tidak ada aksi</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                            <i class="fa-regular fa-calendar-xmark text-3xl mb-2 text-slate-300"></i>
                            <p class="font-semibold">Belum ada jadwal pemeriksaan.</p>
                            <p class="text-[11px] text-slate-400">Klik tombol <strong>+ Jadwal Baru</strong> untuk menambahkan jadwal pemeriksaan tanpa bentrok.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ============================================= -->
<!-- MODAL TAMBAH JADWAL PEMERIKSAAN (UC-13)       -->
<!-- ============================================= -->
<div id="createModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl border border-slate-200 overflow-hidden my-8">
        <div class="bg-navy-900 text-white px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-calendar-plus text-cyan-400 text-lg"></i>
                <h3 class="font-bold text-base text-white">Tambah Jadwal Pemeriksaan Rutin</h3>
            </div>
            <button onclick="closeCreateModal()" class="text-slate-400 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('inspections.store') }}">
            @csrf
            <div class="p-6 space-y-4 text-xs">

                <div class="p-3 bg-cyan-50 border border-cyan-200 rounded-lg text-cyan-900 text-[11px]">
                    <i class="fa-solid fa-circle-info mr-1 text-cyan-600"></i>
                    Sesuai skenario <strong>UC-13</strong>, sistem akan otomatis mendeteksi bentrok jam kerja petugas maupun bentrok di titik air yang sama.
                </div>

                <!-- Pilih Titik Sumber Air -->
                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Titik Sumber Air <span class="text-rose-500">*</span>
                    </label>
                    <select name="water_point_id" required class="w-full p-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 bg-white">
                        <option value="">-- Pilih Titik Sumber Air --</option>
                        @foreach($titikAirList as $t)
                            <option value="{{ $t->titik_air_id }}" {{ old('water_point_id') == $t->titik_air_id ? 'selected' : '' }}>
                                {{ $t->kode_titik }} - {{ $t->nama }} ({{ $t->wilayah }} &bull; {{ $t->status_terkini }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pilih Petugas Lapangan (Aktif vs Nonaktif untuk uji A2) -->
                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Petugas Lapangan <span class="text-rose-500">*</span>
                    </label>
                    <select name="officer_id" required class="w-full p-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 bg-white">
                        <option value="">-- Pilih Petugas Lapangan --</option>
                        <optgroup label="Petugas Aktif (Siap Bertugas)">
                            @foreach($petugasAktif as $p)
                                <option value="{{ $p->id }}" {{ old('officer_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} ({{ $p->phone ?? $p->email }})
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Petugas Nonaktif (Uji Validasi UC-13 A2)">
                            @foreach($semuaPetugas->where('is_active', false) as $pi)
                                <option value="{{ $pi->id }}">
                                    {{ $pi->name }} (Nonaktif - Menolak otomatis)
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <!-- Tanggal & Jam Mulai -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Tanggal & Jam Mulai <span class="text-rose-500">*</span>
                        </label>
                        <input type="datetime-local" name="tanggal_jadwal" value="{{ old('tanggal_jadwal', now()->addDay()->format('Y-m-d\TH:00')) }}" required 
                               class="w-full p-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Durasi (Menit) <span class="text-rose-500">*</span>
                        </label>
                        <select name="durasi_menit" required class="w-full p-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 bg-white">
                            <option value="30" {{ old('durasi_menit') == 30 ? 'selected' : '' }}>30 Menit</option>
                            <option value="60" {{ old('durasi_menit', 60) == 60 ? 'selected' : '' }}>60 Menit (1 Jam)</option>
                            <option value="90" {{ old('durasi_menit') == 90 ? 'selected' : '' }}>90 Menit (1.5 Jam)</option>
                            <option value="120" {{ old('durasi_menit') == 120 ? 'selected' : '' }}>120 Menit (2 Jam)</option>
                            <option value="180" {{ old('durasi_menit') == 180 ? 'selected' : '' }}>180 Menit (3 Jam)</option>
                        </select>
                    </div>
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Catatan / Instruksi Khusus
                    </label>
                    <textarea name="catatan" rows="2" placeholder="Contoh: Bawa alat uji sisa klor dan kalibrasi pH meter..." class="w-full p-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500">{{ old('catatan') }}</textarea>
                </div>

            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-save"></i>
                    <span>Simpan Jadwal Tanpa Bentrok</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================= -->
<!-- MODAL UBAH JADWAL PEMERIKSAAN (UC-13 A3)      -->
<!-- ============================================= -->
<div id="editModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl border border-slate-200 overflow-hidden my-8">
        <div class="bg-navy-900 text-white px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-pen-to-square text-cyan-400 text-lg"></i>
                <h3 class="font-bold text-base text-white">Ubah / Pindahkan Jadwal Pemeriksaan</h3>
            </div>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="editForm" method="POST" action="">
            @csrf
            @method('PATCH')
            <div class="p-6 space-y-4 text-xs">

                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Petugas Lapangan <span class="text-rose-500">*</span>
                    </label>
                    <select name="officer_id" id="editOfficerSelect" required class="w-full p-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 bg-white">
                        @foreach($petugasAktif as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->phone ?? $p->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Tanggal & Jam Baru <span class="text-rose-500">*</span>
                        </label>
                        <input type="datetime-local" name="tanggal_jadwal" id="editTanggalJadwal" required 
                               class="w-full p-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Durasi (Menit) <span class="text-rose-500">*</span>
                        </label>
                        <select name="durasi_menit" id="editDurasiMenit" required class="w-full p-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 bg-white">
                            <option value="30">30 Menit</option>
                            <option value="60">60 Menit (1 Jam)</option>
                            <option value="90">90 Menit (1.5 Jam)</option>
                            <option value="120">120 Menit (2 Jam)</option>
                            <option value="180">180 Menit (3 Jam)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan</label>
                    <textarea name="catatan" id="editCatatan" rows="2" class="w-full p-2.5 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500"></textarea>
                </div>

            </div>

            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Simpan Perubahan Jadwal</span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function openEditModal(item) {
        const form = document.getElementById('editForm');
        form.action = `/inspections/${item.jadwal_id}`;

        document.getElementById('editOfficerSelect').value = item.petugas_id;
        
        // format ISO datetime to YYYY-MM-DDTHH:mm
        const dateObj = new Date(item.tanggal_jadwal);
        const year = dateObj.getFullYear();
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const day = String(dateObj.getDate()).padStart(2, '0');
        const hours = String(dateObj.getHours()).padStart(2, '0');
        const minutes = String(dateObj.getMinutes()).padStart(2, '0');
        document.getElementById('editTanggalJadwal').value = `${year}-${month}-${day}T${hours}:${minutes}`;

        document.getElementById('editDurasiMenit').value = item.durasi_menit;
        document.getElementById('editCatatan').value = item.catatan || '';

        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection
