@extends('layouts.app')

@section('title', 'Tambah Titik Air')
@section('breadcrumb', 'Tambah Titik Air')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Page Header --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-start justify-between gap-4">
            <div class="space-y-2">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-blue-600 text-white shadow-sm">
                        <i class="fa-solid fa-water"></i> PBI-01
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                        <i class="fa-solid fa-tag fa-xs"></i> UC-01.2
                    </span>
                </div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Tambah Titik Sumber Air Baru</h1>
                <p class="text-xs text-slate-500 leading-relaxed">Isi parameter data induk dan tentukan lokasi koordinat di peta.</p>
            </div>
            <a href="{{ route('water-points.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-slate-200 text-slate-600 text-xs font-semibold rounded-lg hover:bg-slate-50 hover:text-slate-900 transition shrink-0">
                <i class="fa-solid fa-arrow-left fa-xs"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Validation Error Toast --}}
    @if ($errors->any())
        <div class="flex gap-3 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800">
            <i class="fa-solid fa-circle-exclamation text-rose-500 mt-0.5 shrink-0"></i>
            <div class="text-xs space-y-1">
                <div class="font-bold">Terdapat kesalahan pengisian data:</div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Form Card --}}
    <form method="POST" action="{{ route('water-points.store') }}" enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @csrf

        {{-- Section: Data Dasar --}}
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-blue-500 text-xs"></i>
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Data Dasar</span>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
            {{-- Nama Titik --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Titik Air <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Sumur Bor RW 03"
                       class="w-full text-xs border @error('nama') border-rose-500 ring-1 ring-rose-300 @else border-slate-300 @enderror rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                @error('nama')
                    <p class="text-[11px] text-rose-600 mt-1 font-medium flex items-center gap-1"><i class="fa-solid fa-triangle-exclamation fa-xs"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Jenis Titik --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Titik Air <span class="text-rose-500">*</span></label>
                <select name="jenis"
                        class="w-full text-xs border @error('jenis') border-rose-500 ring-1 ring-rose-300 @else border-slate-300 @enderror rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">Pilih Jenis</option>
                    @foreach(['Sumur Bor', 'Sumur Gali', 'Mata Air', 'PDAM', 'Kran Umum'] as $jns)
                        <option value="{{ $jns }}" {{ old('jenis') == $jns ? 'selected' : '' }}>{{ $jns }}</option>
                    @endforeach
                </select>
                @error('jenis')
                    <p class="text-[11px] text-rose-600 mt-1 font-medium flex items-center gap-1"><i class="fa-solid fa-triangle-exclamation fa-xs"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Wilayah --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Wilayah <span class="text-rose-500">*</span></label>
                <input type="text" name="wilayah" value="{{ old('wilayah') }}" placeholder="Contoh: RW 03"
                       class="w-full text-xs border @error('wilayah') border-rose-500 ring-1 ring-rose-300 @else border-slate-300 @enderror rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                @error('wilayah')
                    <p class="text-[11px] text-rose-600 mt-1 font-medium flex items-center gap-1"><i class="fa-solid fa-triangle-exclamation fa-xs"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Foto --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    <i class="fa-solid fa-image text-slate-400 fa-xs mr-1"></i>Foto Titik Air
                    <span class="font-normal text-slate-400">(opsional, JPG/PNG, maks 5MB)</span>
                </label>
                <input type="file" name="foto" accept="image/png, image/jpeg, image/jpg"
                       class="w-full text-xs border @error('foto') border-rose-500 ring-1 ring-rose-300 @else border-slate-300 @enderror rounded-lg px-3 py-1.5 file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                @error('foto')
                    <p class="text-[11px] text-rose-600 mt-1 font-medium flex items-center gap-1"><i class="fa-solid fa-triangle-exclamation fa-xs"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Alamat Lengkap --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                <textarea name="alamat" rows="2" placeholder="Contoh: Jl. Melati No. 12, RT 02 / RW 03"
                          class="w-full text-xs border @error('alamat') border-rose-500 ring-1 ring-rose-300 @else border-slate-300 @enderror rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="text-[11px] text-rose-600 mt-1 font-medium flex items-center gap-1"><i class="fa-solid fa-triangle-exclamation fa-xs"></i> {{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Section: Koordinat & Peta --}}
        <div class="px-6 py-4 border-t border-b border-slate-100 bg-slate-50 flex items-center gap-2">
            <i class="fa-solid fa-location-dot text-blue-500 text-xs"></i>
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Koordinat & Peta</span>
        </div>
        <div class="p-6 space-y-4">
            <p class="text-[11px] text-slate-500">Klik pada peta di bawah ini untuk mengisi latitude &amp; longitude secara otomatis, atau geser penanda (marker).</p>

            {{-- Peta Picker --}}
            <div id="picker-map" class="w-full h-64 rounded-xl border border-slate-300 overflow-hidden shadow-inner"></div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Latitude <span class="text-rose-500">*</span> <span class="font-normal text-slate-400">(-90 s/d 90)</span></label>
                    <input type="number" step="any" id="latitude" name="latitude" value="{{ old('latitude', -6.9745210) }}"
                           class="w-full text-xs font-mono border @error('latitude') border-rose-500 ring-1 ring-rose-300 @else border-slate-300 @enderror rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    @error('latitude')
                        <p class="text-[11px] text-rose-600 mt-1 font-medium flex items-center gap-1"><i class="fa-solid fa-triangle-exclamation fa-xs"></i> {{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Longitude <span class="text-rose-500">*</span> <span class="font-normal text-slate-400">(-180 s/d 180)</span></label>
                    <input type="number" step="any" id="longitude" name="longitude" value="{{ old('longitude', 107.6301240) }}"
                           class="w-full text-xs font-mono border @error('longitude') border-rose-500 ring-1 ring-rose-300 @else border-slate-300 @enderror rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    @error('longitude')
                        <p class="text-[11px] text-rose-600 mt-1 font-medium flex items-center gap-1"><i class="fa-solid fa-triangle-exclamation fa-xs"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Footer Actions --}}
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-100 bg-slate-50">
            <a href="{{ route('water-points.index') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-300 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-100 transition">
                <i class="fa-solid fa-xmark fa-xs"></i> Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow transition">
                <i class="fa-solid fa-floppy-disk fa-xs"></i> Simpan Titik Sumber Air
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
