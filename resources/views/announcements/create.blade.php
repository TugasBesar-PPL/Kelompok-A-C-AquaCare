@extends('layouts.app')

@section('title', 'Terbitkan Konten')
@section('breadcrumb', 'Terbitkan Konten')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Page Header --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-start justify-between gap-4">
            <div class="space-y-2">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-purple-600 text-white shadow-sm">
                        <i class="fa-solid fa-bullhorn"></i> PBI-12
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                        <i class="fa-solid fa-tag fa-xs"></i> UC-12.1
                    </span>
                </div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Terbitkan Pengumuman / Edukasi</h1>
                <p class="text-xs text-slate-500 leading-relaxed">Buat artikel edukasi kualitas air bersih atau pengumuman resmi bagi warga.</p>
            </div>
            <a href="{{ route('announcements.index') }}"
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
    <form method="POST" action="{{ route('announcements.store') }}" enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @csrf

        {{-- Section: Metadata Konten --}}
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-purple-500 text-xs"></i>
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Metadata Konten</span>
        </div>
        <div class="p-6 space-y-5">
            {{-- Judul --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Konten <span class="text-rose-500">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}"
                       placeholder="Contoh: Pentingnya Menjaga Kebersihan Sumber Air"
                       class="w-full text-xs border @error('judul') border-rose-500 ring-1 ring-rose-300 @else border-slate-300 @enderror rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
                @error('judul')
                    <p class="text-[11px] text-rose-600 mt-1 font-medium flex items-center gap-1"><i class="fa-solid fa-triangle-exclamation fa-xs"></i> {{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Kategori --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori <span class="text-rose-500">*</span></label>
                    <select name="kategori"
                            class="w-full text-xs border @error('kategori') border-rose-500 ring-1 ring-rose-300 @else border-slate-300 @enderror rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
                        <option value="edukasi" {{ old('kategori') == 'edukasi' ? 'selected' : '' }}>📚 Edukasi Air Bersih</option>
                        <option value="pengumuman" {{ old('kategori') == 'pengumuman' ? 'selected' : '' }}>📢 Pengumuman Operasional</option>
                    </select>
                    @error('kategori')
                        <p class="text-[11px] text-rose-600 mt-1 font-medium flex items-center gap-1"><i class="fa-solid fa-triangle-exclamation fa-xs"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Publikasi --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status Publikasi <span class="text-rose-500">*</span></label>
                    <select name="status_publikasi"
                            class="w-full text-xs border @error('status_publikasi') border-rose-500 ring-1 ring-rose-300 @else border-slate-300 @enderror rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
                        <option value="published" {{ old('status_publikasi') == 'published' ? 'selected' : '' }}>✅ Terbitkan Langsung</option>
                        <option value="draft" {{ old('status_publikasi') == 'draft' ? 'selected' : '' }}>📝 Simpan sebagai Draft</option>
                    </select>
                    @error('status_publikasi')
                        <p class="text-[11px] text-rose-600 mt-1 font-medium flex items-center gap-1"><i class="fa-solid fa-triangle-exclamation fa-xs"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Gambar --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    <i class="fa-solid fa-image text-slate-400 fa-xs mr-1"></i>Gambar Pendukung
                    <span class="font-normal text-slate-400">(opsional, JPG/PNG, maks 5MB)</span>
                </label>
                <input type="file" name="gambar" accept="image/png, image/jpeg, image/jpg"
                       class="w-full text-xs border @error('gambar') border-rose-500 ring-1 ring-rose-300 @else border-slate-300 @enderror rounded-lg px-3 py-1.5 file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition">
                @error('gambar')
                    <p class="text-[11px] text-rose-600 mt-1 font-medium flex items-center gap-1"><i class="fa-solid fa-triangle-exclamation fa-xs"></i> {{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Section: Isi Artikel --}}
        <div class="px-6 py-4 border-t border-b border-slate-100 bg-slate-50 flex items-center gap-2">
            <i class="fa-solid fa-align-left text-purple-500 text-xs"></i>
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Isi Artikel / Pengumuman</span>
        </div>
        <div class="p-6">
            <textarea name="isi" rows="10"
                      placeholder="Tuliskan isi artikel edukasi atau pengumuman secara jelas di sini..."
                      class="w-full text-xs border @error('isi') border-rose-500 ring-1 ring-rose-300 @else border-slate-300 @enderror rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 transition">{{ old('isi') }}</textarea>
            @error('isi')
                <p class="text-[11px] text-rose-600 mt-1 font-medium flex items-center gap-1"><i class="fa-solid fa-triangle-exclamation fa-xs"></i> {{ $message }}</p>
            @enderror
        </div>

        {{-- Footer Actions --}}
        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-100 bg-slate-50">
            <a href="{{ route('announcements.index') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-300 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-100 transition">
                <i class="fa-solid fa-xmark fa-xs"></i> Batal
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-lg shadow transition">
                <i class="fa-solid fa-paper-plane fa-xs"></i> Simpan & Terbitkan
            </button>
        </div>
    </form>

</div>
@endsection
