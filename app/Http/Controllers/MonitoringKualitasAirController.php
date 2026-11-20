<?php

namespace App\Http\Controllers;

use App\Models\BakuMutuParameter;
use App\Models\PengukuranKualitasAir;
use App\Models\TitikSumberAir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonitoringKualitasAirController extends Controller
{
    /**
     * Halaman daftar titik sumber air untuk memilih monitoring / input pengukuran
     */
    public function index()
    {
        $titikList = TitikSumberAir::orderBy('kode_titik')->get();
        return view('monitoring.index', compact('titikList'));
    }

    /**
     * PBI-03: Form Input Hasil Pengukuran Kualitas Air (UC-06)
     */
    public function create(Request $request)
    {
        $titikList = TitikSumberAir::orderBy('kode_titik')->get();
        $selectedTitikId = $request->query('titik_air_id');
        return view('monitoring.input', compact('titikList', 'selectedTitikId'));
    }

    /**
     * PBI-03: Simpan Hasil Pengukuran + Stub Evaluasi Kelayakan PBI-04
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titik_air_id'       => 'required|exists:titik_sumber_air,titik_air_id',
            'tanggal_pengukuran' => 'required|date',
            'jadwal_id'          => 'nullable|integer',
            'ph'                 => 'required|numeric|min:0|max:14',
            'kekeruhan'          => 'required|numeric|min:0|max:1000',
            'tds'                => 'required|numeric|min:0|max:5000',
            'suhu'               => 'required|numeric|min:0|max:100',
            'sisa_klor'          => 'required|numeric|min:0|max:50',
        ], [
            'ph.min' => 'Nilai pH harus berada di antara 0 sampai 14.',
            'ph.max' => 'Nilai pH harus berada di antara 0 sampai 14.',
        ]);

        // Hitung status kelayakan menggunakan fungsi evaluasi (PBI-04)
        $statusKelayakan = $this->evaluateMeasurementStub($validated);

        DB::transaction(function () use ($validated, $statusKelayakan) {
            PengukuranKualitasAir::create([
                'titik_air_id'       => $validated['titik_air_id'],
                'petugas_id'         => Auth::id() ?? 1,
                'jadwal_id'          => $validated['jadwal_id'] ?? null,
                'tanggal_pengukuran' => $validated['tanggal_pengukuran'],
                'ph'                 => $validated['ph'],
                'kekeruhan'          => $validated['kekeruhan'],
                'tds'                => $validated['tds'],
                'suhu'               => $validated['suhu'],
                'sisa_klor'          => $validated['sisa_klor'],
                'status_kelayakan'   => $statusKelayakan,
            ]);

            TitikSumberAir::where('titik_air_id', $validated['titik_air_id'])
                ->update([
                    'status_terkini'      => $statusKelayakan,
                    'pengukuran_terakhir' => $validated['tanggal_pengukuran'],
                ]);
        });

        return redirect()
            ->route('monitoring.show', $validated['titik_air_id'])
            ->with('success', "Hasil pengukuran berhasil disimpan. Status kelayakan: {$statusKelayakan}.");
    }

    /**
     * PBI-05: Halaman Detail Monitoring, Grafik Tren & Riwayat Pengukuran (UC-08 / Gambar 7)
     */
    public function show(Request $request, $id)
    {
        $titik = TitikSumberAir::findOrFail($id);

        $from = $request->query('from');
        $to = $request->query('to');
        $selectedParam = $request->query('parameter', 'kekeruhan');

        $allowedParams = ['kekeruhan', 'ph', 'tds', 'suhu', 'sisa_klor'];
        if (!in_array($selectedParam, $allowedParams, true)) {
            $selectedParam = 'kekeruhan';
        }

        $query = PengukuranKualitasAir::where('titik_air_id', $titik->titik_air_id);

        if ($from) {
            $query->whereDate('tanggal_pengukuran', '>=', $from);
        }
        if ($to) {
            $query->whereDate('tanggal_pengukuran', '<=', $to);
        }

        $riwayatDesc = (clone $query)->orderByDesc('tanggal_pengukuran')->get();
        $riwayatAsc  = (clone $query)->orderBy('tanggal_pengukuran')->get();

        $latest = PengukuranKualitasAir::where('titik_air_id', $titik->titik_air_id)
            ->orderByDesc('tanggal_pengukuran')
            ->first();

        // Standar baku mutu untuk garis batas & status per parameter
        $standards = $this->getActiveStandards();
        $paramStatuses = $latest ? $this->evaluatePerParameter($latest, $standards) : [];

        // Ringkasan Min, Max, Rata-rata (PBI-05)
        $ringkasan = [];
        foreach ($allowedParams as $param) {
            $values = $riwayatAsc->pluck($param)->filter(fn ($v) => $v !== null);
            $ringkasan[$param] = [
                'min' => $values->count() ? round($values->min(), 2) : 0,
                'max' => $values->count() ? round($values->max(), 2) : 0,
                'avg' => $values->count() ? round($values->avg(), 2) : 0,
            ];
        }

        // Data grafik tren
        $chartLabels = $riwayatAsc->map(fn ($item) => $item->tanggal_pengukuran->format('d M'))->values();
        $chartSeries = [];
        foreach ($allowedParams as $param) {
            $chartSeries[$param] = $riwayatAsc->pluck($param)->values();
        }

        return view('monitoring.detail', compact(
            'titik',
            'latest',
            'riwayatDesc',
            'standards',
            'paramStatuses',
            'ringkasan',
            'selectedParam',
            'from',
            'to',
            'chartLabels',
            'chartSeries'
        ));
    }

    /**
     * PBI-05 Endpoint JSON: GET /water-points/{id}/history (sesuai Table 15)
     */
    public function historyApi(Request $request, $id)
    {
        TitikSumberAir::findOrFail($id);

        $query = PengukuranKualitasAir::where('titik_air_id', $id);
        if ($request->filled('from')) {
            $query->whereDate('tanggal_pengukuran', '>=', $request->query('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('tanggal_pengukuran', '<=', $request->query('to'));
        }

        $records = $query->orderBy('tanggal_pengukuran')->get();
        $params = ['ph', 'kekeruhan', 'tds', 'suhu', 'sisa_klor'];

        $ringkasan = collect($params)->map(function ($param) use ($records) {
            $vals = $records->pluck($param);
            return [
                'parameter'  => $param,
                'nilai_min'  => $vals->count() ? round($vals->min(), 2) : null,
                'nilai_maks' => $vals->count() ? round($vals->max(), 2) : null,
                'rata_rata'  => $vals->count() ? round($vals->avg(), 2) : null,
            ];
        });

        return response()->json([
            'data'      => $records,
            'ringkasan' => $ringkasan,
        ]);
    }

    /**
     * Stub sementara untuk PBI-04 (evaluateMeasurement) sesuai rencana Sprint 1 di Proposal
     */
    private function evaluateMeasurementStub(array $data): string
    {
        $standards = $this->getActiveStandards();
        $statuses = $this->evaluatePerParameter((object) $data, $standards);

        if (in_array('Tidak Layak', $statuses, true)) {
            return 'Tidak Layak';
        }
        if (in_array('Waspada', $statuses, true)) {
            return 'Waspada';
        }
        return 'Layak';
    }

    private function evaluatePerParameter(object $data, array $standards): array
    {
        $result = [];
        foreach ($standards as $kode => $std) {
            $val = (float) ($data->{$kode} ?? 0);
            if ($val >= $std['min'] && $val <= $std['max']) {
                $result[$kode] = 'Layak';
            } elseif ($val >= $std['waspada_min'] && $val <= $std['waspada_max']) {
                $result[$kode] = 'Waspada';
            } else {
                $result[$kode] = 'Tidak Layak';
            }
        }
        return $result;
    }

    private function getActiveStandards(): array
    {
        // Default mengacu baku mutu air minum (Permenkes No. 2 Tahun 2023 / Mockup Gambar 7)
        $defaults = [
            'ph'        => ['label' => 'pH',        'unit' => '',     'min' => 6.5, 'max' => 8.5, 'waspada_min' => 6.0, 'waspada_max' => 9.0],
            'kekeruhan' => ['label' => 'Kekeruhan', 'unit' => 'NTU',  'min' => 0.0, 'max' => 3.0, 'waspada_min' => 0.0, 'waspada_max' => 5.0],
            'tds'       => ['label' => 'TDS',       'unit' => 'mg/L', 'min' => 0.0, 'max' => 300, 'waspada_min' => 0.0, 'waspada_max' => 500],
            'suhu'      => ['label' => 'Suhu',      'unit' => '°C',   'min' => 20,  'max' => 30,  'waspada_min' => 18,  'waspada_max' => 33],
            'sisa_klor' => ['label' => 'Sisa klor', 'unit' => 'mg/L', 'min' => 0.2, 'max' => 0.5, 'waspada_min' => 0.1, 'waspada_max' => 1.0],
        ];

        $dbStandards = BakuMutuParameter::orderByDesc('tanggal_berlaku')->get()->unique('kode_parameter');
        foreach ($dbStandards as $row) {
            if (isset($defaults[$row->kode_parameter])) {
                $defaults[$row->kode_parameter]['min'] = (float) ($row->nilai_minimum ?? $defaults[$row->kode_parameter]['min']);
                $defaults[$row->kode_parameter]['max'] = (float) ($row->nilai_maksimum ?? $defaults[$row->kode_parameter]['max']);
                $defaults[$row->kode_parameter]['waspada_min'] = (float) ($row->batas_waspada_min ?? $defaults[$row->kode_parameter]['waspada_min']);
                $defaults[$row->kode_parameter]['waspada_max'] = (float) ($row->batas_waspada_maks ?? $defaults[$row->kode_parameter]['waspada_max']);
            }
        }

        return $defaults;
    }
}