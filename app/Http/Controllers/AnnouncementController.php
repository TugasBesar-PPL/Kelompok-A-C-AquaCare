<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Http\Requests\AnnouncementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * PBI-12: Daftar pengumuman dan edukasi (Admin)
     * Endpoint API: GET /announcements
     * Web Route: GET /admin/announcements
     */
    public function index(Request $request)
    {
        $kategori = $request->input('kategori');
        $query = Pengumuman::latest('tanggal_publikasi');

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $items = $query->paginate($request->input('per_page', 10));

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $items->items(),
                'meta' => [
                    'current_page' => $items->currentPage(),
                    'last_page' => $items->lastPage(),
                    'total' => $items->total(),
                ]
            ]);
        }

        return view('announcements.index', compact('items', 'kategori'));
    }

    /**
     * PBI-12: Halaman portal edukasi & pengumuman untuk Warga
     * Web Route: GET /edukasi
     */
    public function portalWarga(Request $request)
    {
        $kategori = $request->input('kategori');
        $query = Pengumuman::published()->latest('tanggal_publikasi');

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $items = $query->paginate(9);
        return view('announcements.warga', compact('items', 'kategori'));
    }

    /**
     * Web Route: Halaman Form Tambah Pengumuman/Edukasi
     */
    public function create()
    {
        return view('announcements.create');
    }

    /**
     * PBI-12: Terbitkan pengumuman atau artikel edukasi baru
     * Endpoint API: POST /announcements
     */
    public function store(AnnouncementRequest $request)
    {
        $validated = $request->validated();
        $validated['admin_id'] = Auth::id() ?? 1; // Fallback jika belum auth

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('announcements', 'public');
            $validated['gambar'] = $path;
        }

        $validated['status_publikasi'] = $request->input('status_publikasi', 'published');
        $validated['tanggal_publikasi'] = now();

        $content = Pengumuman::create($validated);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Konten berhasil diterbitkan.',
                'data' => $content
            ], 201);
        }

        return redirect()->route('announcements.index')->with('success', 'Konten berhasil diterbitkan.');
    }

    /**
     * PBI-12: Detail satu konten pengumuman/edukasi
     * Endpoint API: GET /announcements/:id
     */
    public function show(Request $request, $id)
    {
        $content = Pengumuman::findOrFail($id);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $content
            ]);
        }

        return view('announcements.show', compact('content'));
    }

    /**
     * Web Route: Halaman Form Edit Konten
     */
    public function edit($id)
    {
        $content = Pengumuman::findOrFail($id);
        return view('announcements.edit', compact('content'));
    }

    /**
     * PBI-12: Perbarui pengumuman atau edukasi
     * Endpoint API: PUT /announcements/:id
     */
    public function update(AnnouncementRequest $request, $id)
    {
        $content = Pengumuman::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('gambar')) {
            if ($content->gambar && Storage::disk('public')->exists($content->gambar)) {
                Storage::disk('public')->delete($content->gambar);
            }
            $path = $request->file('gambar')->store('announcements', 'public');
            $validated['gambar'] = $path;
        }

        $content->update($validated);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Konten berhasil diperbarui.',
                'data' => $content
            ]);
        }

        return redirect()->route('announcements.index')->with('success', 'Konten berhasil diperbarui.');
    }

    /**
     * PBI-12: Hapus konten pengumuman atau edukasi
     * Endpoint API: DELETE /announcements/:id
     */
    public function destroy(Request $request, $id)
    {
        $content = Pengumuman::findOrFail($id);

        if ($content->gambar && Storage::disk('public')->exists($content->gambar)) {
            Storage::disk('public')->delete($content->gambar);
        }

        $content->delete();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Konten berhasil dihapus.'
            ]);
        }

        return redirect()->route('announcements.index')->with('success', 'Konten berhasil dihapus.');
    }
}

