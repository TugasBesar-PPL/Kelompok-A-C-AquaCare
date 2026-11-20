<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\Support\Pbi06TestData;

uses(RefreshDatabase::class, Pbi06TestData::class);

test('report detail works and explains history is unavailable when its table is absent', function () {
    $owner = User::factory()->create();
    $report = $this->createReport($owner);

    $this->assertFalse(Schema::hasTable('riwayat_penanganan'));

    $this->actingAs($owner)
        ->get(route('reports.show', $report->laporan_id))
        ->assertOk()
        ->assertSee('Riwayat penanganan belum tersedia.');
});

test('report detail reads available history without modifying it', function () {
    $owner = User::factory()->create();
    $petugas = User::factory()->create();
    $report = $this->createReport($owner);
    $this->createHistoryTable();

    DB::table('riwayat_penanganan')->insert([
        [
            'laporan_id' => $report->laporan_id,
            'petugas_id' => $petugas->id,
            'tanggal_pembaruan' => now()->subHour(),
            'status_penanganan' => 'Diperiksa',
            'catatan' => 'Petugas sedang memeriksa lokasi.',
            'foto_bukti' => null,
        ],
        [
            'laporan_id' => $report->laporan_id,
            'petugas_id' => $petugas->id,
            'tanggal_pembaruan' => now(),
            'status_penanganan' => 'Diproses',
            'catatan' => 'Perbaikan sedang berlangsung.',
            'foto_bukti' => null,
        ],
    ]);

    $before = DB::table('riwayat_penanganan')
        ->where('laporan_id', $report->laporan_id)
        ->orderBy('penanganan_id')
        ->get()
        ->map(fn ($row) => (array) $row)
        ->all();

    $response = $this->actingAs($owner)->get(route('reports.show', $report->laporan_id));

    $response->assertOk()
        ->assertSee('Diperiksa')
        ->assertSee('Petugas sedang memeriksa lokasi.')
        ->assertSee('Diproses')
        ->assertSee('Perbaikan sedang berlangsung.')
        ->assertDontSee('Riwayat penanganan belum tersedia.');

    $after = DB::table('riwayat_penanganan')
        ->where('laporan_id', $report->laporan_id)
        ->orderBy('penanganan_id')
        ->get()
        ->map(fn ($row) => (array) $row)
        ->all();

    expect($after)->toBe($before);
});

test('report detail explains when no history rows exist', function () {
    $owner = User::factory()->create();
    $report = $this->createReport($owner);
    $this->createHistoryTable();

    $this->actingAs($owner)
        ->get(route('reports.show', $report->laporan_id))
        ->assertOk()
        ->assertSee('Laporan ini belum memiliki riwayat penanganan.');
});
