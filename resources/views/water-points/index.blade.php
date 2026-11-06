@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                <span>⚙️</span> Manajemen Titik Sumber Air (PBI-01)
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Kelola data induk titik sumber air (tambah, lihat, ubah, dan hapus) sebagai lokasi pemantauan.
            </p>
        </div>
        <a href="{{ route('water-points.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow transition self-start sm:self-auto">
            <span>➕</span> Tambah Titik Air Baru
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('water-points.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nama titik / alamat..." class="text-xs border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 focus:ring-2 focus:ring-blue-500">
            
            <select name="jenis" class="text-xs border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Jenis</option>
                @foreach($daftarJenis as $jns)
                    <option value="{{ $jns }}" {{ ($filters['jenis'] ?? '') == $jns ? 'selected' : '' }}>{{ $jns }}</option>
                @endforeach
            </select>

            <select name="wilayah" class="text-xs border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Wilayah</option>
                @foreach($daftarWilayah as $wil)
                    <option value="{{ $wil }}" {{ ($filters['wilayah'] ?? '') == $wil ? 'selected' : '' }}>{{ $wil }}</option>
                @endforeach
            </select>

            <select name="status" class="text-xs border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="Layak" {{ ($filters['status'] ?? '') == 'Layak' ? 'selected' : '' }}>Layak</option>
                <option value="Waspada" {{ ($filters['status'] ?? '') == 'Waspada' ? 'selected' : '' }}>Waspada</option>
                <option value="Tidak Layak" {{ ($filters['status'] ?? '') == 'Tidak Layak' ? 'selected' : '' }}>Tidak Layak</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-3 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition">
                    Cari
                </button>
                <a href="{{ route('water-points.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg text-center transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Data -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Nama Titik</th>
                        <th class="py-3 px-4">Jenis</th>
                        <th class="py-3 px-4">Wilayah & Alamat</th>
                        <th class="py-3 px-4">Koordinat (Lat, Lng)</th>
                        <th class="py-3 px-4">Status Terkini</th>
                        <th class="py-3 px-4">Uji Terakhir</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($waterPoints as $point)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-semibold text-slate-900">
                                {{ $point->nama }}
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px] font-medium border border-slate-200">
                                    {{ $point->jenis }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <div class="font-medium text-slate-800">{{ $point->wilayah }}</div>
                                <div class="text-[11px] text-slate-400 truncate max-w-[200px]">{{ $point->alamat }}</div>
                            </td>
                            <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">
                                {{ number_format($point->latitude, 5) }}, {{ number_format($point->longitude, 5) }}
                            </td>
                            <td class="py-3 px-4">
                                @if($point->status_terkini == 'Layak')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        🟢 Layak
                                    </span>
                                @elseif($point->status_terkini == 'Waspada')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        🟡 Waspada
                                    </span>
                                @elseif($point->status_terkini == 'Tidak Layak')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                        🔴 Tidak Layak
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        ⚪ {{ $point->status_terkini ?? 'Belum Diuji' }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-500 text-[11px]">
                                {{ $point->pengukuran_terakhir ? $point->pengukuran_terakhir->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('water-points.edit', $point->titik_air_id) }}" class="px-2.5 py-1 text-xs font-medium text-blue-600 hover:bg-blue-50 rounded transition">
                                        Ubah
                                    </a>
                                    <form method="POST" action="{{ route('water-points.destroy', $point->titik_air_id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data titik air ini? Pastikan titik belum memiliki riwayat pengukuran.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 text-xs font-medium text-rose-600 hover:bg-rose-50 rounded transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                Belum ada data titik sumber air.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($waterPoints->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $waterPoints->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

