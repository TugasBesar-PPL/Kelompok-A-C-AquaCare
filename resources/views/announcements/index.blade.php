@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                <span>📢</span> Manajemen Pengumuman & Edukasi (PBI-12)
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Terbitkan, perbarui, dan kelola artikel edukasi air bersih serta pengumuman operasional untuk warga.
            </p>
        </div>
        <a href="{{ route('announcements.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow transition self-start sm:self-auto">
            <span>➕</span> Terbitkan Konten Baru
        </a>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
        <span class="text-xs font-semibold text-slate-600">Filter Kategori:</span>
        <a href="{{ route('announcements.index') }}" class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ empty($kategori) ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            Semua
        </a>
        <a href="{{ route('announcements.index', ['kategori' => 'edukasi']) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ $kategori == 'edukasi' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            📚 Edukasi Air Bersih
        </a>
        <a href="{{ route('announcements.index', ['kategori' => 'pengumuman']) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg transition {{ $kategori == 'pengumuman' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            📢 Pengumuman
        </a>
    </div>

    <!-- Table List -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Judul Konten</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Tanggal Publikasi</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($items as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-semibold text-slate-900 max-w-sm">
                                <div>{{ $item->judul }}</div>
                                <div class="text-[11px] text-slate-400 font-normal truncate mt-0.5">{{ Str::limit($item->isi, 80) }}</div>
                            </td>
                            <td class="py-3 px-4">
                                @if($item->kategori == 'edukasi')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-cyan-100 text-cyan-800">
                                        📚 Edukasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800">
                                        📢 Pengumuman
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($item->status_publikasi == 'published')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-500">
                                {{ $item->tanggal_publikasi ? $item->tanggal_publikasi->format('d M Y') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('announcements.edit', $item->pengumuman_id) }}" class="px-2.5 py-1 text-xs font-medium text-blue-600 hover:bg-blue-50 rounded transition">
                                        Ubah
                                    </a>
                                    <form method="POST" action="{{ route('announcements.destroy', $item->pengumuman_id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konten ini?')">
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
                            <td colspan="5" class="py-8 text-center text-slate-400">
                                Belum ada pengumuman atau artikel edukasi yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $items->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

