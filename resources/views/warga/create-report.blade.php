@extends('layouts.app')

@section('title', 'Ajukan Laporan Masalah Air - AquaCare')
@section('breadcrumb-parent', 'WARGA')
@section('breadcrumb-title', 'Ajukan Laporan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/80 p-8 shadow-sm">
        
        <!-- Header Section (Matching Screenshot) -->
        <div class="mb-8">
            <span class="text-[11px] font-bold uppercase tracking-wider text-cyan-600 block mb-1">
                LAYANAN WARGA
            </span>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                Ajukan Laporan Masalah Air
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Ceritakan kondisi air dan lokasi kejadian. Laporan akan tersimpan dengan status awal Menunggu.
            </p>
        </div>

        <form method="POST" action="{{ route('warga.lapor.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Grid 1: Kategori & Wilayah -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Kategori masalah
                    </label>
                    <input type="text" name="kategori_masalah" value="{{ old('kategori_masalah') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 text-sm text-slate-800 placeholder-slate-400 bg-white"
                           placeholder="Contoh: Air keruh">
                    <p class="text-[11px] text-slate-400 mt-1.5">Pilihan umum: Air keruh, Air berbau, Aliran mati, Pipa bocor</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Wilayah
                    </label>
                    <input type="text" name="wilayah" value="{{ old('wilayah') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 text-sm text-slate-800 placeholder-slate-400 bg-white"
                           placeholder="Kelurahan atau kecamatan">
                </div>
            </div>

            <!-- Grid 2: Latitude & Longitude -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Latitude
                    </label>
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 text-sm text-slate-800 placeholder-slate-400 bg-white"
                           placeholder="-6.917464">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Longitude
                    </label>
                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 text-sm text-slate-800 placeholder-slate-400 bg-white"
                           placeholder="107.619123">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="ambilLokasiSaya()" class="text-xs text-cyan-600 hover:text-cyan-700 font-semibold flex items-center gap-1.5">
                    <i class="fa-solid fa-location-crosshairs"></i> Gunakan Koordinat GPS Saya
                </button>
            </div>

            <!-- Alamat kejadian -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Alamat kejadian
                </label>
                <input type="text" name="alamat" value="{{ old('alamat') }}" required
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 text-sm text-slate-800 placeholder-slate-400 bg-white"
                       placeholder="Contoh: Jl. Melati No. 12, RW 03">
            </div>

            <!-- Deskripsi masalah -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Deskripsi masalah
                </label>
                <textarea name="deskripsi" rows="4" required
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 text-sm text-slate-800 placeholder-slate-400 bg-white"
                          placeholder="Jelaskan kondisi air dan kapan masalah terjadi.">{{ old('deskripsi') }}</textarea>
            </div>

            <!-- Upload Foto Pendukung -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Foto Pendukung (Opsional, JPG/PNG maks 5 MB)
                </label>
                <input type="file" name="foto" accept="image/png, image/jpeg"
                       class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 cursor-pointer border border-slate-200 rounded-xl p-2 bg-white">
            </div>

            <!-- Actions -->
            <div class="pt-4 flex items-center gap-4">
                <button type="submit"
                        class="px-6 py-3 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-sm shadow-md shadow-cyan-600/20 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Kirim Laporan</span>
                </button>
                <a href="{{ route('warga.laporan') }}"
                   class="px-5 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>

    </div>
</div>
@endsection

@section('scripts')
<script>
    function ambilLokasiSaya() {
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
            }, function(error) {
                alert("Gagal mendapatkan lokasi GPS: " + error.message);
            });
        } else {
            alert("Browser tidak mendukung GPS Geolocation.");
        }
    }
</script>
@endsection
