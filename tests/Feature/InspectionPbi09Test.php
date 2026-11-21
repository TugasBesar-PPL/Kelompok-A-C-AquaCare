<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\JadwalPemeriksaan;
use App\Models\TitikSumberAir;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InspectionPbi09Test extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $officer1;
    protected $officer2;
    protected $inactiveOfficer;
    protected $waterPoint1;
    protected $waterPoint2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Fathir Admin',
            'email' => 'admin@aquacare.test',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->officer1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@aquacare.test',
            'password' => bcrypt('password123'),
            'role' => 'petugas',
            'is_active' => true,
        ]);

        $this->officer2 = User::create([
            'name' => 'Agus Pratama',
            'email' => 'agus@aquacare.test',
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

        $this->waterPoint1 = TitikSumberAir::create([
            'kode_titik' => 'ST-01',
            'nama' => 'PDAM Tirta Asri RW 01',
            'jenis' => 'PDAM',
            'alamat' => 'Jl. Kenanga No. 5',
            'wilayah' => 'RW 01',
            'status_terkini' => 'Layak',
        ]);

        $this->waterPoint2 = TitikSumberAir::create([
            'kode_titik' => 'ST-02',
            'nama' => 'PDAM Blok A',
            'jenis' => 'PDAM',
            'alamat' => 'Jl. Anggrek No. 2',
            'wilayah' => 'RW 02',
            'status_terkini' => 'Layak',
        ]);
    }

    /**
     * Test PBI-09: Admin dapat melihat daftar jadwal pemeriksaan
     */
    public function test_admin_can_view_inspections_list()
    {
        JadwalPemeriksaan::create([
            'titik_air_id' => $this->waterPoint1->titik_air_id,
            'petugas_id' => $this->officer1->id,
            'tanggal_jadwal' => Carbon::now()->addDay()->setTime(9, 0),
            'durasi_menit' => 60,
            'status_pemeriksaan' => 'dijadwalkan',
            'catatan' => 'Pemeriksaan rutin berkala',
        ]);

        $this->actingAs($this->admin);

        $response = $this->get('/inspections');
        $response->assertStatus(200);
        $response->assertSee('Penjadwalan Pemeriksaan Rutin');
        $response->assertSee('PDAM Tirta Asri RW 01');
        $response->assertSee('Budi Santoso');

        // Test API JSON
        $apiResponse = $this->getJson('/inspections');
        $apiResponse->assertStatus(200);
        $apiResponse->assertJsonFragment(['durasi_menit' => 60]);
    }

    /**
     * Test PBI-09 & UC-13: Admin dapat membuat jadwal tanpa bentrok
     */
    public function test_admin_can_create_schedule_without_conflict()
    {
        $this->actingAs($this->admin);

        $startTime = Carbon::now()->addDays(2)->setTime(10, 0);

        $response = $this->postJson('/inspections', [
            'water_point_id' => $this->waterPoint1->titik_air_id,
            'officer_id' => $this->officer1->id,
            'tanggal_jadwal' => $startTime->toDateTimeString(),
            'durasi_menit' => 60,
            'catatan' => 'Uji kadar sisa klor',
        ]);

        $response->assertStatus(201);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('jadwal_pemeriksaan', [
            'titik_air_id' => $this->waterPoint1->titik_air_id,
            'petugas_id' => $this->officer1->id,
            'durasi_menit' => 60,
            'status_pemeriksaan' => 'dijadwalkan',
        ]);

        // Verifikasi audit log (PBI-17)
        $this->assertDatabaseHas('audit_logs', [
            'aksi' => 'Membuat Jadwal Pemeriksaan',
            'entitas' => 'jadwal_pemeriksaan',
        ]);
    }

    /**
     * Test UC-13 Alur Alternatif A2: Jadwal di waktu lampau ditolak
     */
    public function test_schedule_in_the_past_is_rejected()
    {
        $this->actingAs($this->admin);

        $pastTime = Carbon::now()->subDays(1)->setTime(10, 0);

        $response = $this->postJson('/inspections', [
            'water_point_id' => $this->waterPoint1->titik_air_id,
            'officer_id' => $this->officer1->id,
            'tanggal_jadwal' => $pastTime->toDateTimeString(),
            'durasi_menit' => 60,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['tanggal_jadwal']);
    }

    /**
     * Test UC-13 Alur Alternatif A2: Petugas nonaktif ditolak
     */
    public function test_inactive_officer_is_rejected()
    {
        $this->actingAs($this->admin);

        $futureTime = Carbon::now()->addDays(2)->setTime(10, 0);

        $response = $this->postJson('/inspections', [
            'water_point_id' => $this->waterPoint1->titik_air_id,
            'officer_id' => $this->inactiveOfficer->id,
            'tanggal_jadwal' => $futureTime->toDateTimeString(),
            'durasi_menit' => 60,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['officer_id']);
    }

    /**
     * Test UC-13 Alur Alternatif A1: Deteksi bentrok jadwal petugas yang sama (HTTP 409)
     */
    public function test_overlapping_schedule_for_same_officer_returns_409()
    {
        $this->actingAs($this->admin);

        $baseTime = Carbon::now()->addDays(3)->setTime(9, 0);

        // Jadwal awal: 09:00 - 10:00 (60 menit) untuk Officer 1 di WaterPoint 1
        JadwalPemeriksaan::create([
            'titik_air_id' => $this->waterPoint1->titik_air_id,
            'petugas_id' => $this->officer1->id,
            'tanggal_jadwal' => $baseTime,
            'durasi_menit' => 60,
            'status_pemeriksaan' => 'dijadwalkan',
        ]);

        // Coba buat jadwal bentrok: 09:30 - 10:30 untuk Officer 1 di WaterPoint 2
        $overlappingTime = $baseTime->copy()->addMinutes(30);

        $response = $this->postJson('/inspections', [
            'water_point_id' => $this->waterPoint2->titik_air_id,
            'officer_id' => $this->officer1->id,
            'tanggal_jadwal' => $overlappingTime->toDateTimeString(),
            'durasi_menit' => 60,
        ]);

        $response->assertStatus(409);
        $response->assertJson(['success' => false]);
    }

    /**
     * Test UC-13 Alur Alternatif A1: Deteksi bentrok jadwal titik air yang sama (HTTP 409)
     */
    public function test_overlapping_schedule_for_same_water_point_returns_409()
    {
        $this->actingAs($this->admin);

        $baseTime = Carbon::now()->addDays(4)->setTime(14, 0);

        // Jadwal awal: 14:00 - 15:30 (90 menit) di WaterPoint 1 oleh Officer 1
        JadwalPemeriksaan::create([
            'titik_air_id' => $this->waterPoint1->titik_air_id,
            'petugas_id' => $this->officer1->id,
            'tanggal_jadwal' => $baseTime,
            'durasi_menit' => 90,
            'status_pemeriksaan' => 'dijadwalkan',
        ]);

        // Coba buat jadwal bentrok di WaterPoint 1 oleh Officer 2 pada 14:45
        $overlappingTime = $baseTime->copy()->addMinutes(45);

        $response = $this->postJson('/inspections', [
            'water_point_id' => $this->waterPoint1->titik_air_id,
            'officer_id' => $this->officer2->id,
            'tanggal_jadwal' => $overlappingTime->toDateTimeString(),
            'durasi_menit' => 60,
        ]);

        $response->assertStatus(409);
        $response->assertJson(['success' => false]);
    }

    /**
     * Test UC-13 A3: Admin dapat memindahkan / mengubah jadwal pemeriksaan
     */
    public function test_admin_can_update_schedule()
    {
        $this->actingAs($this->admin);

        $baseTime = Carbon::now()->addDays(5)->setTime(10, 0);

        $jadwal = JadwalPemeriksaan::create([
            'titik_air_id' => $this->waterPoint1->titik_air_id,
            'petugas_id' => $this->officer1->id,
            'tanggal_jadwal' => $baseTime,
            'durasi_menit' => 60,
            'status_pemeriksaan' => 'dijadwalkan',
        ]);

        $newTime = $baseTime->copy()->addHours(3);

        $response = $this->patchJson("/inspections/{$jadwal->jadwal_id}", [
            'officer_id' => $this->officer2->id,
            'tanggal_jadwal' => $newTime->toDateTimeString(),
            'durasi_menit' => 90,
            'catatan' => 'Dipindahkan ke siang hari',
        ]);

        $response->assertStatus(200);
        $jadwal->refresh();
        $this->assertEquals($this->officer2->id, $jadwal->petugas_id);
        $this->assertEquals(90, $jadwal->durasi_menit);
    }

    /**
     * Test UC-14: Petugas lapangan dapat menandai jadwalnya sendiri menjadi Selesai
     */
    public function test_officer_can_mark_own_schedule_completed()
    {
        $jadwal = JadwalPemeriksaan::create([
            'titik_air_id' => $this->waterPoint1->titik_air_id,
            'petugas_id' => $this->officer1->id,
            'tanggal_jadwal' => Carbon::now()->addHour(),
            'durasi_menit' => 60,
            'status_pemeriksaan' => 'dijadwalkan',
        ]);

        // Login sebagai Petugas 1
        $this->actingAs($this->officer1);

        $response = $this->patchJson("/inspections/{$jadwal->jadwal_id}/status", [
            'status' => 'selesai',
        ]);

        $response->assertStatus(200);
        $jadwal->refresh();
        $this->assertEquals('selesai', $jadwal->status_pemeriksaan);
    }

    /**
     * Test UC-14: Petugas tidak dapat menandai jadwal milik petugas lain
     */
    public function test_officer_cannot_mark_other_officer_schedule()
    {
        $jadwal = JadwalPemeriksaan::create([
            'titik_air_id' => $this->waterPoint1->titik_air_id,
            'petugas_id' => $this->officer1->id, // Milik Officer 1
            'tanggal_jadwal' => Carbon::now()->addHour(),
            'durasi_menit' => 60,
            'status_pemeriksaan' => 'dijadwalkan',
        ]);

        // Login sebagai Officer 2 (Bukan pemilik tugas)
        $this->actingAs($this->officer2);

        $response = $this->patchJson("/inspections/{$jadwal->jadwal_id}/status", [
            'status' => 'selesai',
        ]);

        $response->assertStatus(403);
        $jadwal->refresh();
        $this->assertEquals('dijadwalkan', $jadwal->status_pemeriksaan);
    }
}
