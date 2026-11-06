@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Ubah Data Titik Sumber Air</h1>
            <p class="text-xs text-slate-500">Perbarui informasi titik: {{ $point->nama }}</p>
        </div>
        <a href="{{ route('water-points.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
            &larr; Kembali ke Daftar
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs">
            <div class="font-bold mb-1">Terdapat kesalahan pengisian data:</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('water-points.update', $point->titik_air_id) }}" enctype="multipart/form-data" class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nama Titik -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Titik Air *</label>
                <input type="text" name="nama" value="{{ old('nama', $point->nama) }}" required class="w-full text-xs border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Jenis Titik -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Titik Air *</label>
                <select name="jenis" required class="w-full text-xs border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Jenis</option>
                    @foreach(['Sumur Bor', 'Sumur Gali', 'Mata Air', 'PDAM', 'Kran Umum'] as $jns)
                        <option value="{{ $jns }}" {{ old('jenis', $point->jenis) == $jns ? 'selected' : '' }}>{{ $jns }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Wilayah -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Wilayah (RW / Dusun / Kelurahan) *</label>
                <input type="text" name="wilayah" value="{{ old('wilayah', $point->wilayah) }}" required class="w-full text-xs border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Foto -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Foto Titik Air (Biarkan kosong jika tidak diganti)</label>
                <input type="file" name="foto" accept="image/png, image/jpeg, image/jpg" class="w-full text-xs border border-slate-300 rounded-lg px-3 py-1.5 file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @if($point->foto)
                    <p class="text-[10px] text-slate-500 mt-1">Foto saat ini tersimpan: {{ basename($point->foto) }}</p>
                @endif
            </div>

            <!-- Alamat Lengkap -->
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap *</label>
                <textarea name="alamat" rows="2" required class="w-full text-xs border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('alamat', $point->alamat) }}</textarea>
            </div>
        </div>

        <!-- Pemilihan Koordinat & Peta Picker -->
        <div class="border-t border-slate-200 pt-4 space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold text-slate-800">Ubah Posisi Koordinat</h3>
                    <p class="text-[11px] text-slate-500">Geser penanda (marker) atau klik lokasi baru pada peta.</p>
                </div>
            </div>

            <!-- Peta Picker -->
            <div id="picker-map" class="w-full h-64 rounded-lg border border-slate-300 overflow-hidden shadow-inner"></div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Latitude *</label>
                    <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude', $point->latitude) }}" required class="w-full text-xs font-mono border border-slate-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Longitude *</label>
                    <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude', $point->longitude) }}" required class="w-full text-xs font-mono border border-slate-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
            <a href="{{ route('water-points.index') }}" class="px-4 py-2 border border-slate-300 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');

        const initialLat = parseFloat(latInput.value) || -6.9745210;
        const initialLng = parseFloat(lngInput.value) || 107.6301240;

        const map = L.map('picker-map').setView([initialLat, initialLng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);

        let marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

        function updateInputs(lat, lng) {
            latInput.value = lat.toFixed(7);
            lngInput.value = lng.toFixed(7);
        }

        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateInputs(e.latlng.lat, e.latlng.lng);
        });

        marker.on('dragend', function (e) {
            const pos = marker.getLatLng();
            updateInputs(pos.lat, pos.lng);
        });
    });
</script>
@endpush

