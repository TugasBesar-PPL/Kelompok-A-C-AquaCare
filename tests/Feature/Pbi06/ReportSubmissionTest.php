<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Illuminate\Support\Facades\Storage;
use Tests\Support\Pbi06TestData;

uses(RefreshDatabase::class, Pbi06TestData::class);

test('an authenticated resident can submit a report owned by their account', function () {
    $user = App\Models\User::factory()->create();
    $anotherUser = App\Models\User::factory()->create();
    Storage::fake('local');
    Storage::fake('public');

    $payload = $this->validReportPayload();
    $payload['user_id'] = $anotherUser->id;

    $response = $this->actingAs($user)->post(route('reports.store'), $payload);

    $response->assertRedirect(route('reports.create'));
    $this->assertDatabaseHas('laporan_masalah_air', [
        'user_id' => $user->id,
        'kategori_masalah' => 'Air berbau',
        'wilayah' => 'RW 02',
        'status_laporan' => 'Menunggu',
    ]);

    $report = App\Models\LaporanMasalahAir::query()->firstOrFail();
    $this->assertNotSame($anotherUser->id, $report->user_id);
    Storage::disk('local')->assertExists($report->foto_laporan);
    Storage::disk('public')->assertMissing($report->foto_laporan);
});

test('submission rejects missing required fields and a missing photo', function () {
    $user = App\Models\User::factory()->create();

    $response = $this->actingAs($user)->post(route('reports.store'), [
        'kategori' => '',
        'wilayah' => '',
        'lokasi' => ['latitude' => '', 'longitude' => '', 'alamat' => ''],
        'deskripsi' => '',
    ]);

    $response->assertSessionHasErrors([
        'kategori', 'wilayah', 'lokasi.latitude', 'lokasi.longitude',
        'lokasi.alamat', 'deskripsi', 'foto',
    ]);
    $this->assertDatabaseCount('laporan_masalah_air', 0);
});

test('submission rejects invalid coordinates and a non image file', function () {
    $user = App\Models\User::factory()->create();
    $payload = $this->validReportPayload();
    $payload['lokasi']['latitude'] = 91;
    $payload['foto'] = UploadedFile::fake()->create('evidence.pdf', 20, 'application/pdf');

    $response = $this->actingAs($user)->post(route('reports.store'), $payload);

    $response->assertSessionHasErrors(['lokasi.latitude', 'foto']);
    $this->assertDatabaseCount('laporan_masalah_air', 0);
});

test('submission rejects a photo larger than five megabytes', function () {
    $user = App\Models\User::factory()->create();
    $payload = $this->validReportPayload();
    $photo = $this->oversizedValidPng();
    $payload['foto'] = $photo;

    try {
        $response = $this->actingAs($user)->post(route('reports.store'), $payload);

        $response->assertSessionHasErrors('foto');
        $this->assertStringContainsString(
            '5120',
            $response->getSession()->get('errors')->first('foto'),
        );
        $this->assertDatabaseCount('laporan_masalah_air', 0);
    } finally {
        @unlink($photo->getPathname());
    }
});

test('submission maps water_point_id to titik_air_id', function () {
    $user = App\Models\User::factory()->create();
    Storage::fake('local');
    $payload = $this->validReportPayload();
    $payload['water_point_id'] = 98765;

    $this->actingAs($user)
        ->post(route('reports.store'), $payload)
        ->assertRedirect(route('reports.create'));

    $this->assertDatabaseHas('laporan_masalah_air', [
        'user_id' => $user->id,
        'titik_air_id' => 98765,
    ]);
});

test('submission does not create a report when private photo storage returns false', function () {
    $user = App\Models\User::factory()->create();
    $disk = Mockery::mock(FilesystemContract::class);
    $disk->shouldReceive('putFileAs')->once()->andReturn(false);
    Storage::set('local', $disk);

    $response = $this->actingAs($user)->post(route('reports.store'), $this->validReportPayload());

    $response->assertSessionHasErrors('foto');
    $this->assertDatabaseCount('laporan_masalah_air', 0);
});

test('submission does not create a report when private photo storage throws', function () {
    $user = App\Models\User::factory()->create();
    $disk = Mockery::mock(FilesystemContract::class);
    $disk->shouldReceive('putFileAs')->once()->andThrow(new RuntimeException('Storage unavailable.'));
    Storage::set('local', $disk);

    $response = $this->actingAs($user)->post(route('reports.store'), $this->validReportPayload());

    $response->assertSessionHasErrors('foto');
    $this->assertDatabaseCount('laporan_masalah_air', 0);
});

test('failed report insert removes the uploaded photo', function () {
    $user = App\Models\User::factory()->create();
    Storage::fake('local');

    App\Models\LaporanMasalahAir::creating(function ($report) {
        $report->user_id = 999999;
    });

    $response = $this->actingAs($user)->post(route('reports.store'), $this->validReportPayload());

    $response->assertStatus(500);
    $this->assertDatabaseCount('laporan_masalah_air', 0);
    $this->assertSame([], Storage::disk('local')->allFiles());
});

test('a guest cannot submit a report', function () {
    $this->postJson(route('reports.store'), [])->assertUnauthorized();
    $this->assertDatabaseCount('laporan_masalah_air', 0);
});
