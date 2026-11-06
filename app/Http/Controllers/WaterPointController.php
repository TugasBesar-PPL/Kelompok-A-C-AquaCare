<?php

namespace App\Http\Controllers;

use App\Models\TitikSumberAir;
use App\Http\Requests\WaterPointRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WaterPointController extends Controller
{
    /**
     * PBI-01: Menampilkan daftar titik sumber air
     * Endpoint API: GET /water-points
     * Web Route: GET /admin/water-points
     */
    public function index(Request $request)
    {
        $filters = $request->only(['wilayah', 'jenis', 'status', 'q']);
        $waterPoints = TitikSumberAir::filter($filters)
            ->latest('updated_at')
            ->paginate($request->input('per_page', 10));

        // Jika request menginginkan JSON (REST API)
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $waterPoints->items(),
                'meta' => [
                    'current_page' => $waterPoints->currentPage(),
                    'last_page' => $waterPoints->lastPage(),
                    'total' => $waterPoints->total(),
                ]
            ]);
        }

        // Daftar unik untuk opsi dropdown filter di UI
        $daftarWilayah = TitikSumberAir::distinct()->pluck('wilayah');
        $daftarJenis = TitikSumberAir::distinct()->pluck('jenis');

        return view('water-points.index', compact('waterPoints', 'daftarWilayah', 'daftarJenis', 'filters'));
    }

    /**
     * Web Route: Halaman Form Tambah Titik Air
     */
    public function create()
    {
        return view('water-points.create');
    }

    /**
     * PBI-01: Simpan titik sumber air baru
     * Endpoint API: POST /water-points
     */
    public function store(WaterPointRequest $request)
    {
        $validated = $request->validated();

        // Handle upload foto (NFR-02)
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('water-points', 'public');
            $validated['foto'] = $path;
        }

        $validated['status_terkini'] = 'Belum Diuji';

        $point = TitikSumberAir::create($validated);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Titik sumber air berhasil ditambahkan.',
                'data' => $point
            ], 201);
        }

        return redirect()->route('water-points.index')->with('success', 'Titik sumber air berhasil ditambahkan.');
    }

    /**
     * PBI-01: Detail satu titik sumber air
     * Endpoint API: GET /water-points/:id
     */
    public function show(Request $request, $id)
    {
        $point = TitikSumberAir::findOrFail($id);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $point
            ]);
        }

        return view('water-points.show', compact('point'));
    }

    /**
     * Web Route: Halaman Form Edit Titik Air
     */
    public function edit($id)
    {
        $point = TitikSumberAir::findOrFail($id);
        return view('water-points.edit', compact('point'));
    }

    /**
     * PBI-01: Perbarui data titik sumber air
     * Endpoint API: PUT /water-points/:id
     */
    public function update(WaterPointRequest $request, $id)
    {
        $point = TitikSumberAir::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($point->foto && Storage::disk('public')->exists($point->foto)) {
                Storage::disk('public')->delete($point->foto);
            }
            $path = $request->file('foto')->store('water-points', 'public');
            $validated['foto'] = $path;
        }

        $point->update($validated);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Data titik sumber air berhasil diperbarui.',
                'data' => $point
            ]);
        }

        return redirect()->route('water-points.index')->with('success', 'Data titik sumber air berhasil diperbarui.');
    }

    /**
     * PBI-01: Hapus data titik sumber air
     * Endpoint API: DELETE /water-points/:id
     * 
     * Sesuai skenario UC-04 (Alur Alternatif A2):
     * "Titik sudah memiliki pengukuran: penghapusan ditolak."
     */
    public function destroy(Request $request, $id)
    {
        $point = TitikSumberAir::findOrFail($id);

        if ($point->hasPengukuran()) {
            $errorMessage = 'Titik air tidak dapat dihapus karena sudah memiliki riwayat pengukuran kualitas air.';
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 422);
            }
            return redirect()->back()->with('error', $errorMessage);
        }

        // Hapus file foto terkait jika ada
        if ($point->foto && Storage::disk('public')->exists($point->foto)) {
            Storage::disk('public')->delete($point->foto);
        }

        $point->delete();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Titik sumber air berhasil dihapus.'
            ]);
        }

        return redirect()->route('water-points.index')->with('success', 'Titik sumber air berhasil dihapus.');
    }

    /**
     * PBI-10: Peta Sebaran Titik Air
     * Endpoint API: GET /map/water-points
     * Web Route: GET /peta
     */
    public function map(Request $request)
    {
        $filters = $request->only(['status', 'wilayah']);
        $points = TitikSumberAir::filter($filters)->get([
            'titik_air_id',
            'nama',
            'jenis',
            'alamat',
            'wilayah',
            'latitude',
            'longitude',
            'foto',
            'status_terkini',
            'pengukuran_terakhir'
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $points
            ]);
        }

        $daftarWilayah = TitikSumberAir::distinct()->pluck('wilayah');

        return view('water-points.map', compact('points', 'daftarWilayah', 'filters'));
    }
}

