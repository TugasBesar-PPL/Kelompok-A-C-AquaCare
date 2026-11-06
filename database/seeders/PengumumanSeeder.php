<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengumuman;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'admin_id' => 1,
                'judul' => 'Pentingnya Menjaga Kebersihan Sumber Air di Lingkungan Rumah',
                'isi' => "Air bersih merupakan kebutuhan esensial sehari-hari untuk minum, memasak, mandi, dan mencuci.\n\nBeberapa langkah penting menjaga kualitas air:\n1. Pastikan jarak antara sumur gali/bor dengan septic tank minimal 10 meter untuk mencegah kontaminasi bakteri E. Coli.\n2. Lakukan pengurasan bak penampungan air secara berkala minimal seminggu sekali.\n3. Jangan membuang limbah detergen atau sampah kimia di dekat resapan air sumur.",
                'kategori' => 'edukasi',
                'gambar' => null,
                'status_publikasi' => 'published',
                'tanggal_publikasi' => now()->subDays(2),
            ],
            [
                'admin_id' => 1,
                'judul' => 'Kenali Ciri Fisik Air Bersih yang Layak Konsumsi',
                'isi' => "Secara kasat mata, air bersih harus memenuhi standar fisik baku mutu Permenkes:\n- Tidak berwarna (jernih dan bening)\n- Tidak berbau menyengat (bebas dari bau amis, belerang, atau besi)\n- Tidak berasa (hambar dan segar)\n\nApabila mendapati air keruh, berbusa, atau berbau tidak lazim, segera laporkan melalui portal laporan warga agar petugas segera memeriksa.",
                'kategori' => 'edukasi',
                'gambar' => null,
                'status_publikasi' => 'published',
                'tanggal_publikasi' => now()->subDay(),
            ],
            [
                'admin_id' => 1,
                'judul' => 'Jadwal Pemeliharaan Pipa Distribusi Wilayah RW 02 dan RW 03',
                'isi' => "Diberitahukan kepada seluruh warga RW 02 dan RW 03 bahwa pengelola akan melakukan perawatan pipa distribusi dan pembersihan filter pada hari Sabtu pukul 09.00 - 12.00 WIB.\n\nSelama waktu tersebut, aliran air kemungkinan akan mengecil atau terhenti sementara. Mohon warga menampung air secukupnya.",
                'kategori' => 'pengumuman',
                'gambar' => null,
                'status_publikasi' => 'published',
                'tanggal_publikasi' => now(),
            ],
        ];

        foreach ($articles as $art) {
            Pengumuman::updateOrCreate(['judul' => $art['judul']], $art);
        }
    }
}

