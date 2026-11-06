@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Hero Header -->
    <div class="bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-500 rounded-2xl p-8 text-white shadow-lg shadow-blue-500/20 relative overflow-hidden">
        <div class="max-w-2xl relative z-10 space-y-2">
            <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-semibold uppercase tracking-wider text-white">
                Pusat Informasi & Edukasi Warga (PBI-12)
            </span>
            <h1 class="text-3xl font-extrabold tracking-tight">Kesehatan Air Bersih untuk Keluarga</h1>
            <p class="text-sm text-blue-100 leading-relaxed">
                Pelajari standar air bersih, tips pemeliharaan sumur mandiri, dan pantau pengumuman terkini seputar distribusi air di wilayah Anda.
            </p>
        </div>
        <!-- Decorative water splash icon -->
        <div class="absolute -right-6 -bottom-8 text-9xl text-white/10 select-none pointer-events-none font-bold">
            💧
        </div>
    </div>

    <!-- Category Tabs Filter -->
    <div class="flex items-center gap-3 border-b border-slate-200 pb-3">
        <a href="{{ route('announcements.warga') }}" class="px-4 py-2 text-xs font-semibold rounded-lg transition {{ empty($kategori) ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            Semua Konten
        </a>
        <a href="{{ route('announcements.warga', ['kategori' => 'edukasi']) }}" class="px-4 py-2 text-xs font-semibold rounded-lg transition {{ $kategori == 'edukasi' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            📚 Edukasi Air Bersih
        </a>
        <a href="{{ route('announcements.warga', ['kategori' => 'pengumuman']) }}" class="px-4 py-2 text-xs font-semibold rounded-lg transition {{ $kategori == 'pengumuman' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            📢 Pengumuman Operasional
        </a>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($items as $item)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition">
                <!-- Gambar Banner / Placeholder -->
                <div class="h-44 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                    @if($item->gambar)
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-4xl select-none">
                            {{ $item->kategori == 'edukasi' ? '📘' : '📣' }}
                        </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        @if($item->kategori == 'edukasi')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-cyan-600 text-white shadow-sm">
                                📚 Edukasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-600 text-white shadow-sm">
                                📢 Pengumuman
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Konten Body -->
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="text-[11px] text-slate-400 mb-1">
                            📅 {{ $item->tanggal_publikasi ? $item->tanggal_publikasi->format('d M Y') : '-' }}
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm leading-snug hover:text-blue-600 transition">
                            {{ $item->judul }}
                        </h3>
                        <p class="text-xs text-slate-600 mt-2 line-clamp-3 leading-relaxed">
                            {{ $item->isi }}
                        </p>
                    </div>

                    <div class="pt-4 mt-4 border-t border-slate-100">
                        <button onclick="bukaModal({{ json_encode($item) }})" class="text-xs font-semibold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            Baca Selengkapnya &rarr;
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 text-sm bg-white rounded-xl border border-slate-200">
                Belum ada konten publikasi untuk kategori ini.
            </div>
        @endforelse
    </div>

    @if($items->hasPages())
        <div class="mt-6">
            {{ $items->withQueryString()->links() }}
        </div>
    @endif
</div>

<!-- Modal Baca Konten Lengkap -->
<div id="modal-baca" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[85vh] flex flex-col shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-5 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <span id="modal-badge" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold"></span>
            <button onclick="tutupModal()" class="text-slate-400 hover:text-slate-700 text-xl font-bold">&times;</button>
        </div>
        <div class="p-6 overflow-y-auto space-y-4">
            <div id="modal-tanggal" class="text-xs text-slate-400"></div>
            <h2 id="modal-judul" class="text-lg font-bold text-slate-900 leading-snug"></h2>
            <div id="modal-isi" class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line"></div>
        </div>
        <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end">
            <button onclick="tutupModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-lg transition">
                Tutup
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
        document.getElementById('modal-tanggal').innerText = 'Diterbitkan pada: ' + (item.tanggal_publikasi || '');
        
        const badge = document.getElementById('modal-badge');
        if (item.kategori === 'edukasi') {
            badge.innerText = '📚 Edukasi Air Bersih';
            badge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-cyan-100 text-cyan-800';
        } else {
            badge.innerText = '📢 Pengumuman';
            badge.className = 'px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800';
        }

        document.getElementById('modal-baca').classList.remove('hidden');
    }

    function tutupModal() {
        document.getElementById('modal-baca').classList.add('hidden');
    }
</script>
@endpush

