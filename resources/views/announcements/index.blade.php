@extends('layouts.app')

@section('title', 'AquaCare - Manajemen Pengumuman & Edukasi (PBI-12)')
@section('breadcrumb', 'Pengumuman & Edukasi')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold" style="background-color: #f0f9ff; color: #075985;">
                    PBI-12 &bull; PIC: Fadhlan S.
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                    Use Case UC-16 s/d UC-18
                </span>
            </div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Pengumuman &amp; Edukasi Air Bersih</h1>
            <p class="text-xs text-slate-500 mt-1 max-w-2xl">
                Terbitkan, perbarui, dan kelola artikel edukasi air bersih serta pengumuman operasional. Status Draft tidak tampil ke warga.
            </p>
        </div>
        <a href="{{ route('announcements.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-white font-bold text-xs rounded-lg shadow-sm transition-colors shrink-0"
           style="background-color: #0284c7;">
            <i class="fa-solid fa-plus text-sm"></i>
            <span>+ Terbitkan Konten Baru</span>
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-1.5 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
            <a href="{{ route('announcements.index') }}"
               class="px-3 py-1.5 rounded-lg text-xs font-bold transition-colors whitespace-nowrap {{ empty($kategori) ? 'text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}"
               @if(empty($kategori)) style="background-color: #0284c7;" @endif>
                Semua Konten
            </a>
            <a href="{{ route('announcements.index', ['kategori' => 'edukasi']) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors whitespace-nowrap {{ $kategori == 'edukasi' ? 'text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}"
               @if($kategori == 'edukasi') style="background-color: #0891b2;" @endif>
                <i class="fa-solid fa-book mr-1"></i> Edukasi Air Bersih
            </a>
            <a href="{{ route('announcements.index', ['kategori' => 'pengumuman']) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors whitespace-nowrap {{ $kategori == 'pengumuman' ? 'text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}"
               @if($kategori == 'pengumuman') style="background-color: #7c3aed;" @endif>
                <i class="fa-solid fa-bullhorn mr-1"></i> Pengumuman
            </a>
        </div>
        <a href="{{ route('announcements.warga') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-800 flex items-center gap-1 shrink-0">
            <i class="fa-solid fa-eye text-xs"></i> Lihat sebagai Warga
        </a>
    </div>

    <!-- Table List -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[11px]" style="background-color: #f8fafc;">
                        <th class="py-3.5 px-5">Judul Konten</th>
                        <th class="py-3.5 px-5">Kategori</th>
                        <th class="py-3.5 px-5">Status</th>
                        <th class="py-3.5 px-5">Tanggal Publikasi</th>
                        <th class="py-3.5 px-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-5 font-semibold text-slate-900 max-w-sm">
                                <div>{{ $item->judul }}</div>
                                <div class="text-[11px] text-slate-400 font-normal truncate mt-0.5">{{ Str::limit($item->isi, 80) }}</div>
                            </td>
                            <td class="py-3.5 px-5">
                                @if($item->kategori == 'edukasi')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold" style="background-color: #cffafe; color: #155e75;">
                                        <i class="fa-solid fa-book text-[9px]"></i> Edukasi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold" style="background-color: #ede9fe; color: #5b21b6;">
                                        <i class="fa-solid fa-bullhorn text-[9px]"></i> Pengumuman
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5">
                                @if($item->status_publikasi == 'published')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-slate-500">
                                {{ $item->tanggal_publikasi ? $item->tanggal_publikasi->format('d M Y') : '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('announcements.edit', $item->pengumuman_id) }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-bold rounded-lg transition-colors border"
                                       style="color: #0284c7; border-color: #bae6fd; background-color: #f0f9ff;">
                                        <i class="fa-solid fa-pen-to-square"></i> Ubah
                                    </a>
                                    <form method="POST" action="{{ route('announcements.destroy', $item->pengumuman_id) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus konten ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-bold rounded-lg transition-colors border"
                                                style="color: #be123c; border-color: #fecdd3; background-color: #fff1f2;">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-bullhorn text-3xl mb-3 block" style="color: #bae6fd;"></i>
                                <p class="font-medium">Belum ada konten yang dibuat.</p>
                                <p class="text-[11px] mt-1">Mulai dengan menerbitkan pengumuman atau artikel edukasi.</p>
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
