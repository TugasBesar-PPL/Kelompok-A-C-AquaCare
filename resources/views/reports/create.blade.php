@extends('layouts.warga')

@section('title', 'Ajukan Laporan Air | AquaCare')
@section('breadcrumb', 'Ajukan Laporan')

@section('content')
<div class="mx-auto max-w-4xl">
    <section class="mb-6">
        <p class="text-xs font-bold uppercase tracking-widest text-cyan-600">
            Layanan Warga
        </p>
        <h1 class="mt-2 text-2xl font-extrabold text-slate-900 md:text-3xl">
            Ajukan Laporan Masalah Air
        </h1>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
            Ceritakan kondisi air dan lokasi kejadian. Laporan akan tersimpan
            dengan status awal Menunggu.
        </p>
    </section>

    @if (session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800"
             role="status">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800"
             role="alert">
            <strong>Periksa kembali data berikut:</strong>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('reports.store') }}"
        enctype="multipart/form-data"
        class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-8"
    >
        @csrf

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label for="kategori" class="mb-2 block text-sm font-semibold text-slate-700">
                    Kategori masalah
                </label>
                <input
                    id="kategori"
                    name="kategori"
                    type="text"
                    value="{{ old('kategori') }}"
                    placeholder="Contoh: Air keruh"
                    required
                    maxlength="100"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-100"
                >
            </div>

            <div>
                <label for="wilayah" class="mb-2 block text-sm font-semibold text-slate-700">
                    Wilayah
                </label>
                <input
                    id="wilayah"
                    name="wilayah"
                    type="text"
                    value="{{ old('wilayah') }}"
                    placeholder="Kelurahan atau kecamatan"
                    required
                    maxlength="150"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-100"
                >
            </div>

            <div>
                <label for="latitude" class="mb-2 block text-sm font-semibold text-slate-700">
                    Latitude
                </label>
                <input
                    id="latitude"
                    name="lokasi[latitude]"
                    type="number"
                    step="any"
                    min="-90"
                    max="90"
                    value="{{ old('lokasi.latitude') }}"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-100"
                >
            </div>

            <div>
                <label for="longitude" class="mb-2 block text-sm font-semibold text-slate-700">
                    Longitude
                </label>
                <input
                    id="longitude"
                    name="lokasi[longitude]"
                    type="number"
                    step="any"
                    min="-180"
                    max="180"
                    value="{{ old('lokasi.longitude') }}"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-100"
                >
            </div>

            <div class="md:col-span-2">
                <label for="alamat" class="mb-2 block text-sm font-semibold text-slate-700">
                    Alamat kejadian
                </label>
                <input
                    id="alamat"
                    name="lokasi[alamat]"
                    type="text"
                    value="{{ old('lokasi.alamat') }}"
                    required
                    maxlength="1000"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-100"
                >
            </div>

            <div class="md:col-span-2">
                <label for="deskripsi" class="mb-2 block text-sm font-semibold text-slate-700">
                    Deskripsi masalah
                </label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    required
                    maxlength="10000"
                    rows="5"
                    placeholder="Jelaskan kondisi air dan kapan masalah terjadi."
                    class="w-full resize-y rounded-xl border border-slate-300 px-4 py-3 text-sm leading-6 text-slate-800 placeholder:text-slate-400 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-100"
                >{{ old('deskripsi') }}</textarea>
            </div>

            <div class="md:col-span-2">
                <label for="foto" class="mb-2 block text-sm font-semibold text-slate-700">
                    Foto bukti
                </label>
                <input
                    id="foto"
                    name="foto"
                    type="file"
                    accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-cyan-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-cyan-700 hover:file:bg-cyan-100"
                >
                <p class="mt-2 text-xs text-slate-500">
                    Format JPG atau PNG, ukuran maksimal 5 MB.
                </p>
            </div>

            <div class="md:col-span-2">
                <label for="water_point_id" class="mb-2 block text-sm font-semibold text-slate-700">
                    ID titik air <span class="font-normal text-slate-400">(opsional)</span>
                </label>
                <input
                    id="water_point_id"
                    name="water_point_id"
                    type="number"
                    min="1"
                    value="{{ old('water_point_id') }}"
                    placeholder="Isi jika tersedia"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-100"
                >
            </div>

            <div class="md:col-span-2">
                <button
                    type="submit"
                    class="w-full rounded-xl bg-cyan-600 px-6 py-3.5 text-sm font-bold text-white transition hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-offset-2 md:w-auto"
                >
                    Kirim Laporan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection