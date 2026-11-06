# Modul PBI Fadhlan (Sprint 1: Titik Air & Edukasi)

Dokumentasi implementasi kode untuk bagian **Muhammad Fadhlan Setyawan** pada proyek **AquaCare**:
- **Sprint 1 (24 Oktober – 21 November 2026)**:
  - **PBI-01**: Manajemen Data Titik Sumber Air (CRUD & Filter Wilayah/Jenis/Status)
  - **PBI-12**: Pengumuman & Edukasi Air Bersih (Portal Warga & CRUD Konten Admin)
- **Sprint 2 (Mendatang)**:
  - **PBI-10**: Peta Sebaran Titik Air *(Sudah dibackup di branch `sprint-2-pbi-10` dan direktori `sprint-2-pbi-10-backup/`)*

---

## 📁 Struktur File Sprint 1

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── WaterPointController.php       # PBI-01 (CRUD Titik Air)
│   │   └── AnnouncementController.php     # PBI-12 (CRUD Pengumuman & Edukasi)
│   └── Requests/
│       ├── WaterPointRequest.php          # Validasi Titik Air + Foto (Max 5MB)
│       └── AnnouncementRequest.php        # Validasi Artikel/Pengumuman + Gambar
└── Models/
    ├── TitikSumberAir.php                 # Model tabel titik_sumber_air
    └── Pengumuman.php                     # Model tabel pengumuman

database/
├── migrations/
│   ├── 2026_11_06_000001_create_titik_sumber_air_table.php
│   └── 2026_11_06_000002_create_pengumuman_table.php
├── seeders/
│   ├── DatabaseSeeder.php
│   ├── TitikSumberAirSeeder.php
│   └── PengumumanSeeder.php
├── titik_sumber_air.sql
└── pengumuman.sql

resources/views/
├── layouts/
│   └── app.blade.php                      # Layout utama dengan Tailwind CSS
├── water-points/
│   ├── index.blade.php                    # Tabel Admin CRUD Titik Air (PBI-01)
│   ├── create.blade.php                   # Form Tambah Titik Air
│   └── edit.blade.php                     # Form Edit Titik Air
└── announcements/
    ├── index.blade.php                    # Tabel Admin Kelola Konten (PBI-12)
    ├── create.blade.php                   # Form Terbit Konten Baru
    ├── edit.blade.php                     # Form Edit Konten
    └── warga.blade.php                    # Portal Edukasi & Pengumuman Warga

routes/
└── water_and_education.php                # Rute Web & REST API (PBI-01 & PBI-12)
```

---

## 🚀 Cara Menjalankan di Laravel

### 1. Rute di `routes/web.php`
```php
require base_path('routes/water_and_education.php');
```

### 2. Migrasi & Seeder Database
```bash
php artisan migrate
php artisan db:seed
```

### 3. Akses Halaman Web (Sprint 1):
- **Admin Kelola Titik Air (PBI-01)**: `http://localhost:8000/admin/water-points`
- **Portal Edukasi Warga (PBI-12)**: `http://localhost:8000/edukasi`
- **Admin Kelola Pengumuman (PBI-12)**: `http://localhost:8000/admin/announcements`
- **REST API Titik Air**: `http://localhost:8000/api/water-points`
- **REST API Pengumuman**: `http://localhost:8000/api/announcements`

