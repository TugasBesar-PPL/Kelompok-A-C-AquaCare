<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\LaporanMasalahAir;
use App\Models\PengukuranKualitasAir;
use App\Models\RiwayatPenanganan;
use App\Models\TitikSumberAir;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Pengguna (Admin, Petugas, Warga)
        $admin = User::create([
            'name' => 'Fathir Aulia Helmi (Admin)',
            'email' => 'admin@aquacare.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'phone' => '081234567890',
        ]);

        $petugas1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@aquacare.id',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
            'is_active' => true,
            'phone' => '082211334455',
        ]);

        $petugas2 = User::create([
            'name' => 'Agus Pratama',
            'email' => 'agus@aquacare.id',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
            'is_active' => true,
            'phone' => '085722334411',
        ]);

        // Petugas Nonaktif untuk menguji validasi UC-11 (Alur Alternatif A2: Petugas nonaktif ditolak)
        $petugasInactive = User::create([
            'name' => 'Rudi Hartono (Nonaktif)',
            'email' => 'rudi@aquacare.id',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
            'is_active' => false,
            'phone' => '087812345678',
        ]);

        $warga1 = User::create([
            'name' => 'Siti Rahma',
            'email' => 'siti@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'warga',
            'is_active' => true,
            'phone' => '081399887766',
        ]);

        $warga2 = User::create([
            'name' => 'Joko Susilo',
            'email' => 'joko@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'warga',
            'is_active' => true,
            'phone' => '081288776655',
        ]);

        $warga3 = User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'warga',
            'is_active' => true,
            'phone' => '085611223344',
        ]);

        $warga4 = User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'warga',
            'is_active' => true,
            'phone' => '089677889900',
        ]);

        // 2. Titik Sumber Air (Total 16 titik: 12 Layak, 3 Waspada, 1 Tidak Layak)
        $titikData = [
            ['ST-01', 'PDAM Tirta Asri RW 01', 'PDAM', 'Jl. Kenanga No. 5', 'RW 01', -6.917464, 107.619123, 'Layak'],
            ['ST-02', 'PDAM Blok A', 'PDAM', 'Komplek Permata Blok A', 'RW 02', -6.918100, 107.620500, 'Layak'],
            ['ST-03', 'Sumur Bor RW 03', 'Sumur Bor', 'Jl. Melati No. 12', 'RW 03', -6.919200, 107.622100, 'Waspada'],
            ['ST-04', 'Sumur Komunal RW 02', 'Sumur Bor', 'Jl. Cempaka No. 8', 'RW 02', -6.920100, 107.623400, 'Layak'],
            ['ST-05', 'Mata Air Cisitu', 'Mata Air', 'Kawasan Lindung Hulu', 'RW 01', -6.914500, 107.616700, 'Layak'],
            ['ST-06', 'Reservoir Utama', 'PDAM', 'Jl. Panorama Indah', 'RW 04', -6.915800, 107.618000, 'Layak'],
            ['ST-07', 'Mata Air Kenanga', 'Mata Air', 'Jl. Kenanga RW 04', 'RW 04', -6.916900, 107.619800, 'Layak'],
            ['ST-08', 'Sumur Bor Balai Warga', 'Sumur Bor', 'Jl. Flamboyan No. 2', 'RW 05', -6.921300, 107.624500, 'Layak'],
            ['ST-09', 'Sumur Bor RW 06', 'Sumur Bor', 'Jl. Bougenville No. 15', 'RW 06', -6.922400, 107.625600, 'Layak'],
            ['ST-10', 'Pipa Transmisi Barat', 'PDAM', 'Jl. Terusan Sukamaju', 'RW 03', -6.923500, 107.626700, 'Waspada'],
            ['ST-11', 'Sumur Gali RW 05', 'Sumur Gali', 'Gg. Anggrek No. 10', 'RW 05', -6.924600, 107.627800, 'Tidak Layak'],
            ['ST-12', 'Sumur Bor RW 01', 'Sumur Bor', 'Jl. Mawar Raya No. 1', 'RW 01', -6.917900, 107.618900, 'Layak'],
            ['ST-13', 'PDAM Unit Timur', 'PDAM', 'Jl. Gatot Subroto No. 44', 'RW 02', -6.918900, 107.621200, 'Layak'],
            ['ST-14', 'Mata Air Sukajadi', 'Mata Air', 'Bukit Hijau', 'RW 04', -6.915200, 107.617400, 'Layak'],
            ['ST-15', 'Sumur Bor Sekolah', 'Sumur Bor', 'Jl. Melati RW 03', 'RW 03', -6.919800, 107.622800, 'Waspada'],
            ['ST-16', 'Kran Umum RW 06', 'PDAM', 'Jl. Dahlia No. 7', 'RW 06', -6.922900, 107.626100, 'Layak'],
        ];

        $titikMap = [];
        foreach ($titikData as $t) {
            $created = TitikSumberAir::create([
                'kode_titik' => $t[0],
                'nama' => $t[1],
                'jenis' => $t[2],
                'alamat' => $t[3],
                'wilayah' => $t[4],
                'latitude' => $t[5],
                'longitude' => $t[6],
                'status_terkini' => $t[7],
                'pengukuran_terakhir' => Carbon::now()->subHours(rand(2, 48)),
            ]);
            $titikMap[$t[0]] = $created->titik_air_id;
        }

        // 3. Pengukuran Kualitas Air Terbaru (Sesuai Mockup Gambar 8)
        PengukuranKualitasAir::create([
            'titik_air_id' => $titikMap['ST-03'],
            'petugas_id' => $petugas1->id,
            'tanggal_pengukuran' => Carbon::now()->subHours(5),
            'ph' => 7.1,
            'kekeruhan' => 3.8,
            'tds' => 245.0,
            'suhu' => 26.5,
            'sisa_klor' => 0.4,
            'status_kelayakan' => 'Waspada',
        ]);

        PengukuranKualitasAir::create([
            'titik_air_id' => $titikMap['ST-07'],
            'petugas_id' => $petugas2->id,
            'tanggal_pengukuran' => Carbon::now()->subHours(8),
            'ph' => 7.4,
            'kekeruhan' => 1.5,
            'tds' => 180.0,
            'suhu' => 25.8,
            'sisa_klor' => 0.5,
            'status_kelayakan' => 'Layak',
        ]);

        PengukuranKualitasAir::create([
            'titik_air_id' => $titikMap['ST-11'],
            'petugas_id' => $petugas1->id,
            'tanggal_pengukuran' => Carbon::now()->subHours(12),
            'ph' => 5.9,
            'kekeruhan' => 8.2,
            'tds' => 450.0,
            'suhu' => 27.2,
            'sisa_klor' => 0.1,
            'status_kelayakan' => 'Tidak Layak',
        ]);

        PengukuranKualitasAir::create([
            'titik_air_id' => $titikMap['ST-02'],
            'petugas_id' => $petugas2->id,
            'tanggal_pengukuran' => Carbon::now()->subHours(18),
            'ph' => 7.3,
            'kekeruhan' => 1.8,
            'tds' => 210.0,
            'suhu' => 26.0,
            'sisa_klor' => 0.4,
            'status_kelayakan' => 'Layak',
        ]);

        PengukuranKualitasAir::create([
            'titik_air_id' => $titikMap['ST-09'],
            'petugas_id' => $petugas1->id,
            'tanggal_pengukuran' => Carbon::now()->subHours(24),
            'ph' => 7.0,
            'kekeruhan' => 2.1,
            'tds' => 195.0,
            'suhu' => 26.3,
            'sisa_klor' => 0.45,
            'status_kelayakan' => 'Layak',
        ]);

        // 4. Laporan Masalah Air (Persis sesuai Mockup Gambar 8 & PBI-07)
        // LP-0136: Laporan utama yang menunggu verifikasi (sesuai Mockup Gambar 8)
        $lap0136 = LaporanMasalahAir::create([
            'kode_laporan' => 'LP-0136',
            'user_id' => $warga1->id,
            'titik_air_id' => $titikMap['ST-03'],
            'kategori_masalah' => 'Air berbau',
            'wilayah' => 'RW 02',
            'lokasi' => 'Jl. Melati No. 12, RW 02',
            'latitude' => -6.919200,
            'longitude' => 107.622100,
            'deskripsi' => 'Air dari kran berwarna kuning sejak pagi tadi dan berbau tanah yang sangat menyengat.',
            'foto_laporan' => 'https://images.unsplash.com/photo-1548839140-29a749e1bc4e?auto=format&fit=crop&w=600&q=80',
            'tanggal_laporan' => Carbon::parse('2026-10-25 08:30:00'),
            'status_laporan' => 'Menunggu',
            'prioritas' => null,
        ]);

        // LP-0137: Laporan menunggu verifikasi tambahan
        $lap0137 = LaporanMasalahAir::create([
            'kode_laporan' => 'LP-0137',
            'user_id' => $warga2->id,
            'titik_air_id' => $titikMap['ST-03'],
            'kategori_masalah' => 'Air keruh',
            'wilayah' => 'RW 03',
            'lokasi' => 'Jl. Mawar No. 8, RW 03',
            'latitude' => -6.919500,
            'longitude' => 107.622500,
            'deskripsi' => 'Kekeruhan air meningkat tajam setelah hujan lebat kemarin sore, endapan pasir tebal di bak penampungan.',
            'foto_laporan' => 'https://images.unsplash.com/photo-1584820927498-cfe5211fd8bf?auto=format&fit=crop&w=600&q=80',
            'tanggal_laporan' => Carbon::parse('2026-10-25 10:15:00'),
            'status_laporan' => 'Menunggu',
            'prioritas' => null,
        ]);

        // LP-0138: Laporan menunggu verifikasi pipa bocor
        $lap0138 = LaporanMasalahAir::create([
            'kode_laporan' => 'LP-0138',
            'user_id' => $warga3->id,
            'titik_air_id' => $titikMap['ST-01'],
            'kategori_masalah' => 'Pipa bocor',
            'wilayah' => 'RW 01',
            'lokasi' => 'Jl. Kenanga No. 5, RW 01 (depan pos ronda)',
            'latitude' => -6.917500,
            'longitude' => 107.619200,
            'deskripsi' => 'Pipa sambungan utama bocor menyembur keluar dan menggenangi pekarangan pos ronda.',
            'foto_laporan' => 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=600&q=80',
            'tanggal_laporan' => Carbon::parse('2026-10-25 11:45:00'),
            'status_laporan' => 'Menunggu',
            'prioritas' => null,
        ]);

        // LP-0135: Ditugaskan (sesuai Mockup Gambar 8)
        LaporanMasalahAir::create([
            'kode_laporan' => 'LP-0135',
            'user_id' => $warga4->id,
            'titik_air_id' => $titikMap['ST-11'],
            'kategori_masalah' => 'Aliran mati',
            'wilayah' => 'RW 05',
            'lokasi' => 'Gg. Anggrek, RW 05',
            'latitude' => -6.924600,
            'longitude' => 107.627800,
            'deskripsi' => 'Air tidak mengalir sejak pukul 06.00 WIB.',
            'foto_laporan' => null,
            'tanggal_laporan' => Carbon::parse('2026-10-25 07:00:00'),
            'status_laporan' => 'Ditugaskan',
            'prioritas' => 'Rendah',
            'admin_verifikator_id' => $admin->id,
            'petugas_id' => $petugas1->id,
            'catatan_verifikasi' => 'Cek katup valve di pipa distribusi RW 05',
            'tanggal_ditugaskan' => Carbon::parse('2026-10-25 08:00:00'),
        ]);

        // LP-0131: Ditugaskan (sesuai Mockup Gambar 8)
        LaporanMasalahAir::create([
            'kode_laporan' => 'LP-0131',
            'user_id' => $warga3->id,
            'titik_air_id' => $titikMap['ST-01'],
            'kategori_masalah' => 'Pipa bocor',
            'wilayah' => 'RW 01',
            'lokasi' => 'Jl. Kenanga No. 5, RW 01',
            'latitude' => -6.917464,
            'longitude' => 107.619123,
            'deskripsi' => 'Pipa retak sambungan sekunder.',
            'foto_laporan' => null,
            'tanggal_laporan' => Carbon::parse('2026-10-24 14:00:00'),
            'status_laporan' => 'Ditugaskan',
            'prioritas' => 'Sedang',
            'admin_verifikator_id' => $admin->id,
            'petugas_id' => $petugas1->id,
            'catatan_verifikasi' => 'Segera bawa seal tape dan klem pipa 2 inci.',
            'tanggal_ditugaskan' => Carbon::parse('2026-10-24 15:30:00'),
        ]);

        // LP-0128: Diproses (sesuai Mockup Gambar 8)
        $lap0128 = LaporanMasalahAir::create([
            'kode_laporan' => 'LP-0128',
            'user_id' => $warga1->id,
            'titik_air_id' => $titikMap['ST-03'],
            'kategori_masalah' => 'Air keruh',
            'wilayah' => 'RW 03',
            'lokasi' => 'Jl. Melati No. 12, RW 03',
            'latitude' => -6.919200,
            'longitude' => 107.622100,
            'deskripsi' => 'Air keruh berlumpur pekat.',
            'foto_laporan' => null,
            'tanggal_laporan' => Carbon::parse('2026-10-24 07:45:00'),
            'status_laporan' => 'Diproses',
            'prioritas' => 'Tinggi',
            'admin_verifikator_id' => $admin->id,
            'petugas_id' => $petugas2->id,
            'catatan_verifikasi' => 'Laporan prioritas tinggi, segera lakukan backwash filter.',
            'tanggal_ditugaskan' => Carbon::parse('2026-10-24 09:10:00'),
        ]);

        RiwayatPenanganan::create([
            'laporan_id' => $lap0128->laporan_id,
            'petugas_id' => $petugas2->id,
            'tanggal_pembaruan' => Carbon::parse('2026-10-24 11:20:00'),
            'status_penanganan' => 'Diproses',
            'catatan' => 'Pipa distribusi dibilas, endapan dibersihkan, air kembali jernih.',
            'foto_bukti' => null,
        ]);

        // LP-0117: Selesai (sesuai Mockup Gambar 8)
        $lap0117 = LaporanMasalahAir::create([
            'kode_laporan' => 'LP-0117',
            'user_id' => $warga2->id,
            'titik_air_id' => $titikMap['ST-07'],
            'kategori_masalah' => 'Pipa bocor',
            'wilayah' => 'RW 04',
            'lokasi' => 'Jl. Panorama RW 04',
            'latitude' => -6.916900,
            'longitude' => 107.619800,
            'deskripsi' => 'Kebocoran pada pipa distribusi.',
            'foto_laporan' => null,
            'tanggal_laporan' => Carbon::parse('2026-10-12 10:00:00'),
            'status_laporan' => 'Selesai',
            'prioritas' => 'Sedang',
            'admin_verifikator_id' => $admin->id,
            'petugas_id' => $petugas1->id,
            'catatan_verifikasi' => 'Lakukan penggantian pipa fitting',
            'tanggal_ditugaskan' => Carbon::parse('2026-10-12 11:00:00'),
            'tanggal_selesai' => Carbon::parse('2026-10-12 15:45:00'),
        ]);

        RiwayatPenanganan::create([
            'laporan_id' => $lap0117->laporan_id,
            'petugas_id' => $petugas1->id,
            'tanggal_pembaruan' => Carbon::parse('2026-10-12 15:45:00'),
            'status_penanganan' => 'Selesai',
            'catatan' => 'Penggantian fitting pipa PVC 2 inch selesai dilakukan. Kebocoran sudah teratasi.',
            'foto_bukti' => null,
        ]);

        // LP-0109: Selesai (sesuai Mockup Gambar 8)
        LaporanMasalahAir::create([
            'kode_laporan' => 'LP-0109',
            'user_id' => $warga1->id,
            'titik_air_id' => $titikMap['ST-02'],
            'kategori_masalah' => 'Air keruh',
            'wilayah' => 'RW 02',
            'lokasi' => 'Komplek Permata RW 02',
            'latitude' => -6.918100,
            'longitude' => 107.620500,
            'deskripsi' => 'Kekeruhan ringan saat pagi hari.',
            'foto_laporan' => null,
            'tanggal_laporan' => Carbon::parse('2026-10-08 08:30:00'),
            'status_laporan' => 'Selesai',
            'prioritas' => 'Rendah',
            'admin_verifikator_id' => $admin->id,
            'petugas_id' => $petugas2->id,
            'catatan_verifikasi' => 'Flushing kran hidran RW 02',
            'tanggal_ditugaskan' => Carbon::parse('2026-10-08 09:00:00'),
            'tanggal_selesai' => Carbon::parse('2026-10-08 12:00:00'),
        ]);

        // LP-0102: Ditolak (sesuai Mockup Gambar 8)
        LaporanMasalahAir::create([
            'kode_laporan' => 'LP-0102',
            'user_id' => $warga4->id,
            'titik_air_id' => null,
            'kategori_masalah' => 'Air berbau',
            'wilayah' => 'RW 06',
            'lokasi' => 'Jl. Bougenville RW 06',
            'latitude' => -6.922400,
            'longitude' => 107.625600,
            'deskripsi' => 'Bau lumpur dari saluran selokan depan rumah.',
            'foto_laporan' => null,
            'tanggal_laporan' => Carbon::parse('2026-10-03 13:20:00'),
            'status_laporan' => 'Ditolak',
            'prioritas' => null,
            'admin_verifikator_id' => $admin->id,
            'catatan_verifikasi' => 'Laporan ditolak: Objek yang dilaporkan adalah selokan drainase kota, bukan jaringan air bersih AquaCare.',
            'tanggal_selesai' => null,
        ]);

        // 5. Audit Log (PBI-17 stub / log records)
        AuditLog::createAuditLog(
            $admin->id,
            'Verifikasi Laporan Diterima',
            'laporan_masalah_air',
            'LP-0128',
            'Laporan LP-0128 diverifikasi dan ditugaskan kepada Agus Pratama dengan prioritas Tinggi.'
        );

        AuditLog::createAuditLog(
            $admin->id,
            'Verifikasi Laporan Ditolak',
            'laporan_masalah_air',
            'LP-0102',
            'Laporan LP-0102 ditolak. Alasan: Objek laporan bukan jaringan air bersih AquaCare.'
        );
    }
}
