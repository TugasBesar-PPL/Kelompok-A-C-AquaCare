<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateBakuMutuRequest;
use App\Models\BakuMutuParameter;
use App\Services\BakuMutuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BakuMutuController extends Controller
{
    public function __construct(private readonly BakuMutuService $bakuMutuService) {}

    // ── Admin Web UI ────────────────────────────────────────────────────────

    public function manage(): View
    {
        return view('baku-mutu.index', [
            'parameters' => $this->bakuMutuService->versiBerlaku(),
        ]);
    }

    public function editPage(string $kode): View
    {
        $parameter = BakuMutuParameter::where('kode_parameter', $kode)
            ->orderByDesc('tanggal_berlaku')
            ->firstOrFail();

        return view('baku-mutu.edit', compact('parameter'));
    }

    public function historyPage(string $kode): View
    {
        return view('baku-mutu.history', [
            'history' => $this->bakuMutuService->riwayat($kode),
        ]);
    }

    /**
     * Handle PUT dari halaman edit admin (form biasa, bukan JSON).
     */
    public function updateWeb(UpdateBakuMutuRequest $request, string $kode): RedirectResponse
    {
        $this->bakuMutuService->simpanVersiBaru(
            $kode,
            $request->validated(),
            $request->user()?->id,
        );

        return redirect()
            ->route('baku-mutu.admin.index')
            ->with('status', 'Versi baku mutu berhasil disimpan.');
    }

    // ── Public / Petugas View & JSON API ────────────────────────────────────

    /**
     * GET /water-standards — daftar versi yang berlaku saat ini.
     * Mengembalikan View jika dibuka di browser, atau JSON jika request API.
     */
    public function index(Request $request): JsonResponse|View
    {
        $parameters = $this->bakuMutuService->versiBerlaku();

        if (! $request->expectsJson()) {
            return view('baku-mutu.index', [
                'parameters' => $parameters,
            ]);
        }

        $formatted = $parameters->map(fn (BakuMutuParameter $p) => $this->parameterData($p));

        return response()->json(['data' => $formatted]);
    }

    /**
     * PUT /water-standards/{kode} — simpan versi baru via API.
     */
    public function update(UpdateBakuMutuRequest $request, string $kode): JsonResponse
    {
        $parameter = $this->bakuMutuService->simpanVersiBaru(
            $kode,
            $request->validated(),
            $request->user()?->id,
        );

        return response()->json(['data' => $this->parameterData($parameter) + [
            'diubah_oleh' => $parameter->diubah_oleh,
        ]], 201);
    }

    /**
     * GET /water-standards/{kode}/history — riwayat versi.
     * Mengembalikan View jika dibuka di browser, atau JSON jika request API.
     */
    public function history(Request $request, string $kode): JsonResponse|View
    {
        $history = $this->bakuMutuService->riwayat($kode);

        if (! $request->expectsJson()) {
            return view('baku-mutu.history', [
                'history' => $history,
            ]);
        }

        $formatted = $history->map(fn (BakuMutuParameter $p) => $this->parameterData($p) + [
            'diubah_oleh' => $p->diubah_oleh,
        ]);

        return response()->json(['data' => $formatted]);
    }

    // ── Helper ──────────────────────────────────────────────────────────────

    private function parameterData(BakuMutuParameter $parameter): array
    {
        return [
            ...$parameter->only([
                'kode_parameter',
                'nama_parameter',
                'satuan',
                'nilai_minimum',
                'nilai_maksimum',
                'batas_waspada_min',
                'batas_waspada_maks',
            ]),
            'tanggal_berlaku' => $parameter->tanggal_berlaku->toDateString(),
        ];
    }
}
