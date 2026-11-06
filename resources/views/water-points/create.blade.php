@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Tambah Titik Sumber Air Baru</h1>
            <p class="text-xs text-slate-500">Isi parameter data induk dan tentukan lokasi koordinat di peta.</p>
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

    <form method="POST" action="{{ route('water-points.store') }}" enctype="multipart/form-data" class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nama Titik -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Titik Air *</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Sumur Bor RW 03" class="w-full text-xs border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Jenis Titik -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Titik Air *</label>
                <select name="jenis" required class="w-full text-xs border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Jenis</option>
                    <option value="Sumur Bor" {{ old('jenis') == 'Sumur Bor' ? 'selected' : '' }}>Sumur Bor</option>
                    <option value="Sumur Gali" {{ old('jenis') == 'Sumur Gali' ? 'selected' : '' }}>Sumur Gali</option>
                    <option value="Mata Air" {{ old('jenis') == 'Mata Air' ? 'selected' : '' }}>Mata Air</option>
                    <option value="PDAM" {{ old('jenis') == 'PDAM' ? 'selected' : '' }}>PDAM</option>
                    <option value="Kran Umum" {{ old('jenis') == 'Kran Umum' ? 'selected' : '' }}>Kran Umum</option>
                </select>
            </div>

            <!-- Wilayah -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Wilayah (RW / Dusun / Kelurahan) *</label>
                <input type="text" name="wilayah" value="{{ old('wilayah') }}" required placeholder="Contoh: RW 03" class="w-full text-xs border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Foto -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Foto Titik Air (Opsional, Max 5MB JPG/PNG)</label>
                <input type="file" name="foto" accept="image/png, image/jpeg, image/jpg" class="w-full text-xs border border-slate-300 rounded-lg px-3 py-1.5 file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <!-- Alamat Lengkap -->
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap *</label>
                <textarea name="alamat" rows="2" required placeholder="Contoh: Jl. Melati No. 12, RT 02 / RW 03" class="w-full text-xs border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('alamat') }}</textarea>
            </div>
        </div>

        <!-- Pemilihan Koordinat & Peta Picker -->
        <div class="border-t border-slate-200 pt-4 space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold text-slate-800">Tentukan Posisi Koordinat</h3>
                    <p class="text-[11px] text-slate-500">Klik pada peta di bawah ini untuk mengisi latitude & longitude secara otomatis.</p>
                </div>
            </div>

            <!-- Peta Picker -->
            <div id="picker-map" class="w-full h-64 rounded-lg border border-slate-300 overflow-hidden shadow-inner"></div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Latitude *</label>
                    <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude', -6.9745210) }}" required class="w-full text-xs font-mono border border-slate-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Longitude *</label>
                    <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude', 107.6301240) }}" required class="w-full text-xs font-mono border border-slate-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
            <a href="{{ route('water-points.index') }}" class="px-4 py-2 border border-slate-300 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow transition">
                Simpan Titik Sumber Air
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

        const map = L.map('picker-map').setView([initialLat, initialLng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);

        let marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

        function updateInputs(lat, lng) {
            latInput.value = lat.toFixed(7);
            lngInput.value = lng.toFixed(7);
        }

        // Event klik di peta
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            updateInputs(e.latlng.lat, e.latlng.lng);
        });

        // Event drag marker
        marker.on('dragend', function (e) {
            const pos = marker.getLatLng();
            updateInputs(pos.lat, pos.lng);
        });

        // Event jika user mengubah input angka secara manual
        function onManualInput() {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.panTo([lat, lng]);
            }
        }
        latInput.addEventListener('change', onManualInput);
        lngInput.addEventListener('change', onManualInput);
    });
</script>
@endpush

