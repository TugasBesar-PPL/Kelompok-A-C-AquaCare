<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\LaporanMasalahAir;
use App\Models\TitikSumberAir;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificationPbi07Test extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $activeOfficer;
    protected $inactiveOfficer;
    protected $warga;
    protected $waterPoint;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup users
        $this->admin = User::create([
            'name' => 'Fathir Admin',
            'email' => 'admin@aquacare.test',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->activeOfficer = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@aquacare.test',
            'password' => bcrypt('password123'),
            'role' => 'petugas',
            'is_active' => true,
        ]);

        $this->inactiveOfficer = User::create([
            'name' => 'Rudi Hartono (Nonaktif)',
            'email' => 'rudi@aquacare.test',
            'password' => bcrypt('password123'),
            'role' => 'petugas',
            'is_active' => false,
        ]);

        $this->warga = User::create([
            'name' => 'Siti Rahma',
            'email' => 'siti@gmail.test',
            'password' => bcrypt('password123'),
            'role' => 'warga',
            'is_active' => true,
        ]);

        $this->waterPoint = TitikSumberAir::create([
            'kode_titik' => 'ST-03',
            'nama' => 'Sumur Bor RW 03',
            'jenis' => 'Sumur Bor',
            'alamat' => 'Jl. Melati No. 12',
            'wilayah' => 'RW 03',
            'status_terkini' => 'Waspada',
        ]);
    }

    /**
     * Test PBI-07: Menampilkan daftar laporan menunggu verifikasi
     */
    public function test_admin_can_view_verification_list()
    {
        $report = LaporanMasalahAir::create([
            'kode_laporan' => 'LP-0136',
            'user_id' => $this->warga->id,
            'titik_air_id' => $this->waterPoint->titik_air_id,
            'kategori_masalah' => 'Air berbau',
            'wilayah' => 'RW 02',
            'lokasi' => 'Jl. Melati No. 12, RW 02',
            'deskripsi' => 'Air berbau tanah pekat.',
            'status_laporan' => 'Menunggu',
        ]);

        $this->actingAs($this->admin);

        $response = $this->get('/reports/verification');
        $response->assertStatus(200);
        $response->assertSee('LP-0136');
        $response->assertSee('Air berbau');
        $response->assertSee('Menunggu');

        // Test JSON API response
        $apiResponse = $this->getJson('/reports/verification');
        $apiResponse->assertStatus(200);
        $apiResponse->assertJsonFragment(['kode_laporan' => 'LP-0136']);
    }

    /**
     * Test PBI-07 & UC-11 Skenario Utama: Menerima laporan dan menugaskan petugas aktif
     */
    public function test_admin_can_accept_and_assign_report_to_active_officer()
    {
        $report = LaporanMasalahAir::create([
            'kode_laporan' => 'LP-0136',
            'user_id' => $this->warga->id,
            'kategori_masalah' => 'Air keruh',
            'wilayah' => 'RW 03',
            'lokasi' => 'Jl. Mawar No. 8',
            'deskripsi' => 'Air keruh berlumpur.',
            'status_laporan' => 'Menunggu',
        ]);

        $this->actingAs($this->admin);

        $response = $this->patchJson("/reports/{$report->laporan_id}/verification", [
            'keputusan' => 'diterima',
            'officer_id' => $this->activeOfficer->id,
            'prioritas' => 'Tinggi',
            'catatan' => 'Tolong cek filter pipa utama.',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Verifikasi perubahan database
        $report->refresh();
        $this->assertEquals('Ditugaskan', $report->status_laporan);
        $this->assertEquals($this->activeOfficer->id, $report->petugas_id);
        $this->assertEquals($this->admin->id, $report->admin_verifikator_id);
        $this->assertEquals('Tinggi', $report->prioritas);
        $this->assertEquals('Tolong cek filter pipa utama.', $report->catatan_verifikasi);
        $this->assertNotNull($report->tanggal_ditugaskan);

        // Verifikasi audit log (PBI-17)
        $log = AuditLog::where('entitas_id', 'LP-0136')->first();
        $this->assertNotNull($log);
        $this->assertEquals('Verifikasi Laporan Diterima', $log->aksi);
    }

    /**
     * Test PBI-07 & UC-11 Alur Alternatif A1: Menolak laporan wajib mengisi alasan penolakan
     */
    public function test_admin_must_provide_reason_when_rejecting_report()
    {
        $report = LaporanMasalahAir::create([
            'kode_laporan' => 'LP-0137',
            'user_id' => $this->warga->id,
            'kategori_masalah' => 'Air berbau',
            'wilayah' => 'RW 06',
            'lokasi' => 'Jl. Bougenville RW 06',
            'deskripsi' => 'Saluran selokan kotor.',
            'status_laporan' => 'Menunggu',
        ]);

        $this->actingAs($this->admin);

        // Uji jika alasan_penolakan kosong: harus ditolak oleh validasi
        $response = $this->patchJson("/reports/{$report->laporan_id}/verification", [
            'keputusan' => 'ditolak',
            'alasan_penolakan' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['alasan_penolakan']);

        // Sekarang kirim dengan alasan penolakan yang valid
        $validResponse = $this->patchJson("/reports/{$report->laporan_id}/verification", [
            'keputusan' => 'ditolak',
            'alasan_penolakan' => 'Bukan bagian jaringan air bersih AquaCare.',
        ]);

        $validResponse->assertStatus(200);

        $report->refresh();
        $this->assertEquals('Ditolak', $report->status_laporan);
        $this->assertEquals('Bukan bagian jaringan air bersih AquaCare.', $report->catatan_verifikasi);

        // Verifikasi audit log
        $log = AuditLog::where('entitas_id', 'LP-0137')->first();
        $this->assertNotNull($log);
        $this->assertEquals('Verifikasi Laporan Ditolak', $log->aksi);
    }

    /**
     * Test PBI-07 & UC-11 Alur Alternatif A2: Penugasan ke petugas nonaktif ditolak
     */
    public function test_assigning_to_inactive_officer_is_rejected()
    {
        $report = LaporanMasalahAir::create([
            'kode_laporan' => 'LP-0138',
            'user_id' => $this->warga->id,
            'kategori_masalah' => 'Pipa bocor',
            'wilayah' => 'RW 01',
            'lokasi' => 'Jl. Kenanga No. 5',
            'deskripsi' => 'Pipa bocor.',
            'status_laporan' => 'Menunggu',
        ]);

        $this->actingAs($this->admin);

        $response = $this->patchJson("/reports/{$report->laporan_id}/verification", [
            'keputusan' => 'diterima',
            'officer_id' => $this->inactiveOfficer->id, // Petugas nonaktif
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['officer_id']);

        // Pastikan status laporan tetap Menunggu
        $report->refresh();
        $this->assertEquals('Menunggu', $report->status_laporan);
        $this->assertNull($report->petugas_id);
    }

    /**
     * Test Dashboard Admin sesuai Mockup Gambar 8
     */
    public function test_dashboard_displays_mockup_components()
    {
        $this->actingAs($this->admin);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Ringkasan Operasional');
        $response->assertSee('Titik Layak');
        $response->assertSee('Titik Waspada');
        $response->assertSee('Titik Tidak Layak');
        $response->assertSee('Lap. Masuk');
        $response->assertSee('Laporan Warga Terbaru');
        $response->assertSee('Sebaran Status Titik');
        $response->assertSee('Pengukuran Terbaru');
    }
}

