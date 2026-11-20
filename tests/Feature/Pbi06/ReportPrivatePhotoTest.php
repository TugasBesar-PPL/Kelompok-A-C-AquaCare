<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\Support\Pbi06TestData;

uses(RefreshDatabase::class, Pbi06TestData::class);

test('the owner can view their private report photo', function () {
    Storage::fake('local');
    $photo = UploadedFile::fake()->image('report.png', 20, 20);
    $path = $photo->store('laporan', 'local');
    $owner = User::factory()->create();
    $report = $this->createReport($owner, ['foto_laporan' => $path]);

    $response = $this->actingAs($owner)->get(route('reports.photo', $report->laporan_id));

    $response->assertOk()
        ->assertHeader('Content-Type', 'image/png')
        ->assertHeader('X-Content-Type-Options', 'nosniff');
    $this->assertTrue($response->headers->getCacheControlDirective('private'));
    $this->assertTrue($response->headers->getCacheControlDirective('no-store'));
});

test('another resident cannot view a report photo', function () {
    Storage::fake('local');
    $path = UploadedFile::fake()->image('report.png')->store('laporan', 'local');
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $report = $this->createReport($owner, ['foto_laporan' => $path]);

    $this->actingAs($other)
        ->get(route('reports.photo', $report->laporan_id))
        ->assertNotFound();
});

test('private photo endpoint rejects traversal paths and non image mime types', function () {
    Storage::fake('local');
    $owner = User::factory()->create();
    $traversalReport = $this->createReport($owner, ['foto_laporan' => '../secret.png']);
    $textPath = 'laporan/not-an-image.txt';
    Storage::disk('local')->put($textPath, 'not an image');
    $textReport = $this->createReport($owner, ['foto_laporan' => $textPath]);

    $this->actingAs($owner)
        ->get(route('reports.photo', $traversalReport->laporan_id))
        ->assertNotFound();

    $this->actingAs($owner)
        ->get(route('reports.photo', $textReport->laporan_id))
        ->assertNotFound();
});

test('a guest cannot view a private report photo', function () {
    $owner = User::factory()->create();
    $report = $this->createReport($owner);

    $this->getJson(route('reports.photo', $report->laporan_id))->assertUnauthorized();
});

test('private evidence must belong to the requested owned report', function () {
    Storage::fake('local');
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $ownedReport = $this->createReport($owner);
    $otherReport = $this->createReport($other);
    $this->createHistoryTable();

    $photoPath = UploadedFile::fake()->image('evidence.png', 20, 20)->store('bukti', 'local');
    $historyId = DB::table('riwayat_penanganan')->insertGetId([
        'laporan_id' => $otherReport->laporan_id,
        'petugas_id' => $owner->id,
        'tanggal_pembaruan' => now(),
        'status_penanganan' => 'Diproses',
        'catatan' => 'Bukti milik laporan lain.',
        'foto_bukti' => $photoPath,
    ], 'penanganan_id');

    $this->actingAs($owner)
        ->get(route('reports.history-photo', [
            'id' => $ownedReport->laporan_id,
            'penangananId' => $historyId,
        ]))
        ->assertNotFound();

    $this->actingAs($other)
        ->get(route('reports.history-photo', [
            'id' => $otherReport->laporan_id,
            'penangananId' => $historyId,
        ]))
        ->assertOk();
});
