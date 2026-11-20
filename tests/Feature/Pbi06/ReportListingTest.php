<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\Pbi06TestData;

uses(RefreshDatabase::class, Pbi06TestData::class);

test('a resident sees only their own reports', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $ownedReport = $this->createReport($owner);
    $otherReport = $this->createReport($other, ['kategori_masalah' => 'Air berasa']);

    $response = $this->actingAs($owner)->get(route('reports.index'));

    $response->assertOk()
        ->assertSee('Air keruh')
        ->assertSee('RW 01')
        ->assertSee('Menunggu');

    $listedIds = $response->viewData('reports')->getCollection()->pluck('laporan_id')->all();
    $this->assertContains($ownedReport->laporan_id, $listedIds);
    $this->assertNotContains($otherReport->laporan_id, $listedIds);
});

test('status filter selects the exact status, including the string zero', function () {
    $owner = User::factory()->create();
    $menunggu = $this->createReport($owner, ['status_laporan' => 'Menunggu']);
    $zeroStatus = $this->createReport($owner, ['status_laporan' => '0']);

    $response = $this->actingAs($owner)->get(route('reports.index', ['status' => '0']));

    $response->assertOk()
        ->assertSee('value="0" selected', false);

    $listedIds = $response->viewData('reports')->getCollection()->pluck('laporan_id')->all();
    $this->assertSame([$zeroStatus->laporan_id], $listedIds);
    $this->assertNotContains($menunggu->laporan_id, $listedIds);
});

test('listing paginates ten reports and preserves the status filter', function () {
    $owner = User::factory()->create();

    foreach (range(1, 11) as $number) {
        $this->createReport($owner, ['status_laporan' => 'Selesai', 'deskripsi' => "Laporan {$number}"]);
    }

    $response = $this->actingAs($owner)->get(route('reports.index', ['status' => 'Selesai']));

    $response->assertOk()
        ->assertSee(e(route('reports.index', ['status' => 'Selesai', 'page' => 2])), false);
    $this->assertCount(10, $response->viewData('reports')->items());
    $this->assertSame(11, $response->viewData('reports')->total());

    $secondPage = $this->actingAs($owner)->get(route('reports.index', ['status' => 'Selesai', 'page' => 2]));
    $secondPage->assertOk();
    $this->assertCount(1, $secondPage->viewData('reports')->items());
    $this->assertSame('Selesai', $secondPage->viewData('reports')->items()[0]->status_laporan);
});

test('listing distinguishes no reports, no filter matches, and an out of range page', function () {
    $owner = User::factory()->create();

    $this->actingAs($owner)->get(route('reports.index'))
        ->assertOk()
        ->assertSee('Belum ada laporan');

    $this->createReport($owner, ['status_laporan' => 'Menunggu']);

    $this->actingAs($owner)->get(route('reports.index', ['status' => 'Ditolak']))
        ->assertOk()
        ->assertSee('Tidak ada laporan dengan status ini');

    $this->actingAs($owner)->get(route('reports.index', ['page' => 999]))
        ->assertOk()
        ->assertSee('Halaman ini tidak berisi laporan');
});

test('a guest cannot view the report list', function () {
    $this->getJson(route('reports.index'))->assertUnauthorized();
});
