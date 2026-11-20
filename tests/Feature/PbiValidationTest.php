<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\TitikSumberAir;
use App\Models\Pengumuman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PbiValidationTest extends TestCase
{
    use RefreshDatabase;

    // ==========================================
    // 1. PBI-01: Validasi WaterPoint (Web & API)
    // ==========================================

    public function test_pbi01_web_store_fails_when_required_fields_are_missing(): void
    {
        $response = $this->post('/admin/water-points', []);
        $response->assertSessionHasErrors(['nama', 'jenis', 'alamat', 'wilayah', 'latitude', 'longitude']);
    }

    public function test_pbi01_web_store_fails_when_coordinates_are_out_of_range(): void
    {
        $response = $this->post('/admin/water-points', [
            'nama' => 'Titik Uji',
            'jenis' => 'Sumur Bor',
            'alamat' => 'Jl. Uji No. 1',
            'wilayah' => 'RW 01',
            'latitude' => 95.0, // Di luar rentang -90..90
            'longitude' => 200.0, // Di luar rentang -180..180
        ]);

        $response->assertSessionHasErrors(['latitude', 'longitude']);
    }

    public function test_pbi01_api_store_returns_json_422_on_validation_failure(): void
    {
        $response = $this->postJson('/api/water-points', [
            'nama' => 'Titik Air API',
            // Field wajib lainnya dikosongkan
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => ['jenis', 'alamat', 'wilayah', 'latitude', 'longitude']
            ]);
    }

    public function test_pbi01_store_succeeds_with_valid_data(): void
    {
        $response = $this->post('/admin/water-points', [
            'nama' => 'Sumur Bor Baru Uji Validasi',
            'jenis' => 'Sumur Bor',
            'alamat' => 'Jl. Sukamaju No. 10',
            'wilayah' => 'RW 09',
            'latitude' => -6.9123456,
            'longitude' => 107.6123456,
        ]);

        $response->assertRedirect('/admin/water-points');
        $this->assertDatabaseHas('titik_sumber_air', [
            'nama' => 'Sumur Bor Baru Uji Validasi',
            'wilayah' => 'RW 09',
        ]);
    }

    // ==========================================
    // 2. PBI-12: Validasi Announcement (Web & API)
    // ==========================================

    public function test_pbi12_web_store_fails_when_required_fields_missing(): void
    {
        $response = $this->post('/admin/announcements', []);
        $response->assertSessionHasErrors(['judul', 'isi', 'kategori']);
    }

    public function test_pbi12_fails_when_category_is_invalid(): void
    {
        $response = $this->post('/admin/announcements', [
            'judul' => 'Judul Artikel',
            'isi' => 'Konten artikel penjelasan kualitas air.',
            'kategori' => 'kategori_ilegal', // Bukan edukasi/pengumuman
        ]);

        $response->assertSessionHasErrors(['kategori']);
    }

    public function test_pbi12_api_store_returns_json_422_on_validation_failure(): void
    {
        $response = $this->postJson('/api/announcements', [
            'judul' => 'Judul API Saja',
            // 'isi' dan 'kategori' kosong
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => ['isi', 'kategori']
            ]);
    }

    public function test_pbi12_store_succeeds_with_valid_data(): void
    {
        $response = $this->post('/admin/announcements', [
            'judul' => 'Panduan Sanitasi Air Sumur Gali',
            'isi' => 'Berikut langkah menjaga kebersihan sumur...',
            'kategori' => 'edukasi',
            'status_publikasi' => 'published',
        ]);

        $response->assertRedirect('/admin/announcements');
        $this->assertDatabaseHas('pengumuman', [
            'judul' => 'Panduan Sanitasi Air Sumur Gali',
            'kategori' => 'edukasi',
        ]);
    }

    public function test_pbi12_portal_warga_only_displays_published_content(): void
    {
        // 1. Buat konten Published
        Pengumuman::create([
            'admin_id' => 1,
            'judul' => 'Artikel Published Terlihat',
            'isi' => 'Isi artikel ini dapat dibaca warga.',
            'kategori' => 'edukasi',
            'status_publikasi' => 'published',
            'tanggal_publikasi' => now(),
        ]);

        // 2. Buat konten Draft
        Pengumuman::create([
            'admin_id' => 1,
            'judul' => 'Artikel Draft Tersembunyi',
            'isi' => 'Isi artikel ini masih draft.',
            'kategori' => 'edukasi',
            'status_publikasi' => 'draft',
            'tanggal_publikasi' => now(),
        ]);

        // 3. Akses portal warga
        $response = $this->get('/edukasi');
        $response->assertStatus(200);
        $response->assertSee('Artikel Published Terlihat');
        $response->assertDontSee('Artikel Draft Tersembunyi');
    }
}
