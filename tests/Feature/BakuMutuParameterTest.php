<?php

use Database\Seeders\BakuMutuParameterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(BakuMutuParameterSeeder::class);
});

// ── API: GET /water-standards ───────────────────────────────────────────────

test('it lists the active water standard parameters', function () {
    $this->getJson('/water-standards')
        ->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonFragment(['kode_parameter' => 'ph']);
});

// ── Web UI: admin pages ─────────────────────────────────────────────────────

test('it displays seeded parameters on the admin index page', function () {
    $this->get('/admin/water-standards')
        ->assertOk()
        ->assertViewIs('baku-mutu.index')
        ->assertSee('Baku Mutu Parameter Air')
        ->assertSee('pH')
        ->assertSee('Kekeruhan')
        ->assertSee('Riwayat');
});

test('it displays the edit form for a valid parameter', function () {
    $this->get('/admin/water-standards/ph/edit')
        ->assertOk()
        ->assertViewIs('baku-mutu.edit')
        ->assertSee('Ubah Baku Mutu: pH');
});

test('it displays the history page for a valid parameter', function () {
    $this->get('/admin/water-standards/ph/riwayat')
        ->assertOk()
        ->assertViewIs('baku-mutu.history')
        ->assertSee('Riwayat Baku Mutu: pH');
});

// ── API: PUT /water-standards/{kode} ────────────────────────────────────────

test('it saves updated limits as a new version', function () {
    $effectiveDate = now()->addDay()->toDateString();

    $this->putJson('/water-standards/ph', [
        'nilai_minimum'      => 6.60,
        'nilai_maksimum'     => 8.40,
        'batas_waspada_min'  => 6.10,
        'batas_waspada_maks' => 8.90,
        'tanggal_berlaku'    => $effectiveDate,
    ])->assertCreated()
      ->assertJsonPath('data.kode_parameter', 'ph')
      ->assertJsonPath('data.nilai_minimum', '6.60');
});

test('it exposes version history after an update', function () {
    $effectiveDate = now()->addDay()->toDateString();

    $this->putJson('/water-standards/ph', [
        'nilai_minimum'      => 6.60,
        'nilai_maksimum'     => 8.40,
        'batas_waspada_min'  => 6.10,
        'batas_waspada_maks' => 8.90,
        'tanggal_berlaku'    => $effectiveDate,
    ])->assertCreated();

    $this->getJson('/water-standards/ph/history')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.tanggal_berlaku', $effectiveDate);
});

test('active listing still returns the original version on the same day', function () {
    $effectiveDate = now()->addDay()->toDateString();

    $this->putJson('/water-standards/ph', [
        'nilai_minimum'      => 6.60,
        'nilai_maksimum'     => 8.40,
        'batas_waspada_min'  => 6.10,
        'batas_waspada_maks' => 8.90,
        'tanggal_berlaku'    => $effectiveDate,
    ])->assertCreated();

    $currentParameters = collect($this->getJson('/water-standards')->json('data'));
    expect($currentParameters->firstWhere('kode_parameter', 'ph')['nilai_minimum'])->toBe('6.50');
});

// ── Validation ──────────────────────────────────────────────────────────────

test('it rejects overlapping normal and warning limits', function () {
    $this->putJson('/water-standards/ph', [
        'nilai_minimum'      => 6.60,
        'nilai_maksimum'     => 8.40,
        // waspada_min > nilai_minimum → invalid
        'batas_waspada_min'  => 6.70,
        // waspada_maks < nilai_maksimum → invalid
        'batas_waspada_maks' => 8.30,
        'tanggal_berlaku'    => now()->addDay()->toDateString(),
    ])->assertUnprocessable()
      ->assertJsonValidationErrors(['batas_waspada_min', 'batas_waspada_maks']);
});

test('it rejects a duplicate effective date for the same parameter', function () {
    $this->putJson('/water-standards/ph', [
        'nilai_minimum'      => 6.60,
        'nilai_maksimum'     => 8.40,
        'batas_waspada_min'  => 6.10,
        'batas_waspada_maks' => 8.90,
        'tanggal_berlaku'    => now()->addDay()->toDateString(),
    ])->assertCreated();

    // Same date again → must fail
    $this->putJson('/water-standards/ph', [
        'nilai_minimum'      => 6.70,
        'nilai_maksimum'     => 8.30,
        'batas_waspada_min'  => 6.20,
        'batas_waspada_maks' => 8.80,
        'tanggal_berlaku'    => now()->addDay()->toDateString(),
    ])->assertUnprocessable()
      ->assertJsonValidationErrors(['tanggal_berlaku']);
});
