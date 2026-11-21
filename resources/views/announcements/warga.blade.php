@extends('layouts.app')

@section('title', 'Portal Warga')
@section('breadcrumb', 'Portal Edukasi Warga')

@section('content')
<div class="space-y-8">

    {{-- Hero Header --}}
    <div class="bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-500 rounded-2xl p-8 text-white shadow-lg shadow-blue-500/20 relative overflow-hidden">
        <div class="max-w-2xl relative z-10 space-y-3">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-white/20 backdrop-blur text-white">
                    <i class="fa-solid fa-bullhorn"></i> PBI-12
                </span>
                <span class="inline-block px-3 py-1 bg-white/15 backdrop-blur rounded-full text-xs font-semibold uppercase tracking-wider text-white/90">
                    Pusat Informasi & Edukasi Warga
                </span>
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight">Kesehatan Air Bersih untuk Keluarga</h1>
            <p class="text-sm text-blue-100 leading-relaxed">
                Pelajari standar air bersih, tips pemeliharaan sumur mandiri, dan pantau pengumuman terkini seputar distribusi air di wilayah Anda.
            </p>
        </div>
        {{-- Decorative --}}
        <div class="absolute -right-6 -bottom-8 text-[120px] text-white/10 select-none pointer-events-none leading-none">
            <i class="fa-solid fa-droplet"></i>
        </div>
    </div>

    {{-- Category Tabs Filter --}}
    <div class="flex items-center gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('announcements.warga') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-lg transition
                  {{ empty($kategori) ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-layer-group fa-xs"></i> Semua Konten
        </a>
        <a href="{{ route('announcements.warga', ['kategori' => 'edukasi']) }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-lg transition
                  {{ $kategori == 'edukasi' ? 'bg-cyan-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-book-open fa-xs"></i> Edukasi Air Bersih
        </a>
        <a href="{{ route('announcements.warga', ['kategori' => 'pengumuman']) }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-lg transition
                  {{ $kategori == 'pengumuman' ? 'bg-purple-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            <i class="fa-solid fa-megaphone fa-xs"></i> Pengumuman Operasional
        </a>
    </div>

    {{-- Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($items as $item)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                {{-- Gambar Banner / Placeholder --}}
                <div class="h-44 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-5xl text-slate-300 select-none">
                            @if($item->kategori == 'edukasi')
                                <i class="fa-solid fa-book-open"></i>
                            @else
                                <i class="fa-solid fa-megaphone"></i>
                            @endif
                        </div>
                    @endif
                    {{-- Category Badge --}}
                    <div class="absolute top-3 left-3">
                        @if($item->kategori == 'edukasi')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-cyan-600 text-white shadow-sm">
                                <i class="fa-solid fa-book-open fa-xs"></i> Edukasi
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-600 text-white shadow-sm">
                                <i class="fa-solid fa-megaphone fa-xs"></i> Pengumuman
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Konten Body --}}
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="text-[11px] text-slate-400 mb-1.5 flex items-center gap-1">
                            <i class="fa-regular fa-calendar fa-xs"></i>
                            {{ $item->tanggal_publikasi ? $item->tanggal_publikasi->format('d M Y') : '-' }}
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm leading-snug hover:text-blue-600 transition">
                            {{ $item->judul }}
                        </h3>
                        <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                            {{ $item->isi }}
                        </p>
                    </div>

                    <div class="pt-4 mt-4 border-t border-slate-100">
                        <button onclick="bukaModal({{ json_encode($item) }})"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                            Baca Selengkapnya <i class="fa-solid fa-arrow-right fa-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-200">
                <div class="text-5xl text-slate-200 mb-4"><i class="fa-solid fa-newspaper"></i></div>
                <p class="text-sm font-semibold text-slate-500">Belum ada konten untuk kategori ini</p>
                <p class="text-xs text-slate-400 mt-1">Coba pilih kategori lain atau kunjungi kembali nanti.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($items->hasPages())
        <div class="mt-6">
            {{ $items->withQueryString()->links() }}
        </div>
    @endif

</div>

{{-- Modal Baca Konten Lengkap --}}
<div id="modal-baca" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[85vh] flex flex-col shadow-2xl overflow-hidden">
        <div class="p-5 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <span id="modal-badge" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold"></span>
            <button onclick="tutupModal()" class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto space-y-4">
            <div id="modal-tanggal" class="text-xs text-slate-400 flex items-center gap-1">
                <i class="fa-regular fa-calendar fa-xs"></i> <span id="modal-tanggal-text"></span>
            </div>
            <h2 id="modal-judul" class="text-lg font-bold text-slate-900 leading-snug"></h2>
            <div id="modal-isi" class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line"></div>
        </div>
        <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end">
            <button onclick="tutupModal()"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-lg transition">
                <i class="fa-solid fa-xmark fa-xs"></i> Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function bukaModal(item) {
        document.getElementById('modal-judul').innerText = item.judul;
        document.getElementById('modal-isi').innerText = item.isi;
        document.getElementById('modal-tanggal-text').innerText = item.tanggal_publikasi
            ? 'Diterbitkan: ' + item.tanggal_publikasi
            : '';

        const badge = document.getElementById('modal-badge');
        if (item.kategori === 'edukasi') {
            badge.innerHTML = '<i class="fa-solid fa-book-open fa-xs mr-1"></i> Edukasi Air Bersih';
            badge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-cyan-100 text-cyan-800';
        } else {
            badge.innerHTML = '<i class="fa-solid fa-megaphone fa-xs mr-1"></i> Pengumuman';
            badge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800';
        }

        document.getElementById('modal-baca').classList.remove('hidden');
    }

    function tutupModal() {
        document.getElementById('modal-baca').classList.add('hidden');
    }

    // Tutup modal klik di luar
    document.getElementById('modal-baca').addEventListener('click', function(e) {
        if (e.target === this) tutupModal();
    });
</script>
@endpush
