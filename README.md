# AquaCare - Bagian Fadhlan (Sprint 1)

Platform monitoring dan pelaporan air bersih, Tugas Besar PPL Kelompok A-C.
README ini isinya bagian gw aja.

Muhammad Fadhlan Setyawan, Fullstack & UI/UX  
Branch: `KelompokC_Fadhlan`  
PBI Sprint 1: PBI-01 (TP-1) dan PBI-12 (TP-12). PBI-10 (peta) masuk Sprint 2.

## Tech stack

- Laravel 12 (Blade) + Tailwind CSS
- Database: SQLite (`database/database.sqlite`) buat development
- Test: PHPUnit, pake SQLite in-memory jadi data dev ga kesentuh

## Cara jalanin

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Buka `http://127.0.0.1:8000`

| Halaman | URL |
|---|---|
| Admin kelola titik air | /admin/water-points |
| Admin kelola konten | /admin/announcements |
| Edukasi warga | /edukasi |
| API titik air | /api/water-points |
| API pengumuman | /api/announcements |

## PBI-01: Manajemen Data Titik Sumber Air

**Tujuan:** nyediain data induk titik sumber air. Fitur lain (pengukuran, jadwal, peta, dashboard) butuh data ini.

**Yang udah jalan:**
- CRUD titik air: nama, jenis, alamat, wilayah, latitude, longitude, foto (opsional)
- Cari nama/alamat, filter jenis, wilayah, status
- Validasi pake Form Request, pesan error bahasa Indonesia di bawah field
- Web (Blade) dan REST API, API balikin JSON 422 kalau validasi gagal

**Aturan validasi:**
- nama, jenis, alamat, wilayah wajib, maks 255 karakter
- latitude -90 sampai 90, longitude -180 sampai 180
- foto opsional, JPG/PNG, maks 5 MB

**Belum:** aturan "titik yang udah punya pengukuran ga bisa dihapus". Nunggu tabel pengukuran (PBI-03) dari Krisna masuk.

## PBI-12: Pengumuman dan Edukasi Air Bersih

**Tujuan:** ningkatin kesadaran warga soal air bersih lewat pengumuman dan artikel edukasi.

**Yang udah jalan:**
- Admin bikin, ubah, hapus konten (judul, isi, tipe pengumuman/edukasi, gambar)
- Warga baca daftar dan detail di /edukasi, bisa filter per kategori
- Status publikasi: Draft & Published (warga di `/edukasi` cuma bisa baca konten yang berstatus Published)
- Validasi Form Request + pesan error bahasa Indonesia, API balikin JSON 422

**Aturan validasi:**
- judul dan isi wajib
- tipe cuma `pengumuman` atau `edukasi`
- gambar opsional, JPG/PNG, maks 5 MB

## Catatan

- Data di tabel dan artikel masih dummy dari seeder
- Kolom "Status Terkini" dan "Uji Terakhir" baru bener-bener keisi setelah PBI-03/04 (Krisna) masuk
- Login dan role admin itu fitur dasar punya Arrival, belum ada di branch gw, jadi sementara halaman admin belum diproteksi
- Endpoint di Laravel pake prefix (`/admin/...`, `/api/...`), beda dikit dari proposal (`/water-points`)

## Status

| PBI | Sprint | Status |
|---|---|---|
| PBI-01 | 1 | CRUD, filter, validasi jalan. Aturan hapus menyusul |
| PBI-12 | 1 | CRUD, tampil ke warga, validasi jalan |
| PBI-10 | 2 | Sprint 2, belum dikerjain di branch ini |
