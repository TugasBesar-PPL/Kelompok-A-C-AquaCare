@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Ubah Konten Pengumuman / Edukasi</h1>
            <p class="text-xs text-slate-500">Edit artikel atau pengumuman: {{ $content->judul }}</p>
        </div>
        <a href="{{ route('announcements.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
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

    <form method="POST" action="{{ route('announcements.update', $content->pengumuman_id) }}" enctype="multipart/form-data" class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-5">
        @csrf
        @method('PUT')

        <!-- Judul -->
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Konten *</label>
            <input type="text" name="judul" value="{{ old('judul', $content->judul) }}" required class="w-full text-xs border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Kategori -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Konten *</label>
                <select name="kategori" required class="w-full text-xs border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 focus:ring-2 focus:ring-blue-500">
                    <option value="edukasi" {{ old('kategori', $content->kategori) == 'edukasi' ? 'selected' : '' }}>📚 Edukasi Air Bersih</option>
                    <option value="pengumuman" {{ old('kategori', $content->kategori) == 'pengumuman' ? 'selected' : '' }}>📢 Pengumuman Operasional</option>
                </select>
            </div>

            <!-- Status Publikasi -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Status Publikasi *</label>
                <select name="status_publikasi" class="w-full text-xs border border-slate-300 rounded-lg px-3 py-2 bg-slate-50 focus:ring-2 focus:ring-blue-500">
                    <option value="published" {{ old('status_publikasi', $content->status_publikasi) == 'published' ? 'selected' : '' }}>Terbitkan Langsung (Published)</option>
                    <option value="draft" {{ old('status_publikasi', $content->status_publikasi) == 'draft' ? 'selected' : '' }}>Simpan sebagai Draft</option>
                </select>
            </div>
        </div>

        <!-- Gambar -->
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Gambar Pendukung (Biarkan kosong jika tidak diganti)</label>
            <input type="file" name="gambar" accept="image/png, image/jpeg, image/jpg" class="w-full text-xs border border-slate-300 rounded-lg px-3 py-1.5 file:mr-3 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            @if($content->gambar)
                <p class="text-[10px] text-slate-500 mt-1">Gambar saat ini: {{ basename($content->gambar) }}</p>
            @endif
        </div>

        <!-- Isi Materi -->
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Isi Artikel / Pengumuman *</label>
            <textarea name="isi" rows="8" required class="w-full text-xs border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('isi', $content->isi) }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
            <a href="{{ route('announcements.index') }}" class="px-4 py-2 border border-slate-300 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

