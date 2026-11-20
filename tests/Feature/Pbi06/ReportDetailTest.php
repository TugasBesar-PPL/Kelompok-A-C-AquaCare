<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\Support\Pbi06TestData;

uses(RefreshDatabase::class, Pbi06TestData::class);

test('the owner can view report detail without changing its status', function () {
    $owner = User::factory()->create();
    $reportDate = Carbon::parse('2026-02-03 04:05:00');
    $report = $this->createReport($owner, [
        'kategori_masalah' => 'Air keruh',
        'wilayah' => 'RW 07',
        'deskripsi' => 'Air tampak keruh dan berbau.',
        'lokasi' => ['latitude' => -6.3, 'longitude' => 106.9, 'alamat' => 'Jalan Mawar 7'],
        'tanggal_laporan' => $reportDate,
        'status_laporan' => 'Menunggu',
    ]);

    $response = $this->actingAs($owner)->get(route('reports.show', $report->laporan_id));

    $response->assertOk()
        ->assertSee((string) $report->laporan_id)
        ->assertSee('Air keruh')
        ->assertSee('RW 07')
        ->assertSee('-6.3')
        ->assertSee('106.9')
        ->assertSee('Jalan Mawar 7')
        ->assertSee('Air tampak keruh dan berbau.')
        ->assertSee('03/02/2026 04:05')
        ->assertSee('Menunggu')
        ->assertSee(route('reports.photo', $report->laporan_id));

    $this->assertDatabaseHas('laporan_masalah_air', [
        'laporan_id' => $report->laporan_id,
        'status_laporan' => 'Menunggu',
    ]);
});

test('another resident cannot view a report detail', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $report = $this->createReport($owner);

    $this->actingAs($other)
        ->get(route('reports.show', $report->laporan_id))
        ->assertNotFound();
});

test('a guest cannot view report detail', function () {
    $owner = User::factory()->create();
    $report = $this->createReport($owner);

    $this->getJson(route('reports.show', $report->laporan_id))->assertUnauthorized();
});
