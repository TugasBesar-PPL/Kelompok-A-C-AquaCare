# Modul PBI Fadhlan (Titik Air, Peta & Edukasi)

Dokumentasi implementasi kode untuk bagian **Muhammad Fadhlan Setyawan** pada proyek **AquaCare**:
- **PBI-01**: Manajemen Data Titik Sumber Air
- **PBI-10**: Peta Sebaran Titik Air (Leaflet.js)
- **PBI-12**: Pengumuman & Edukasi Air Bersih

---

## 📁 Struktur File yang Dibuat

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── WaterPointController.php       # PBI-01 (CRUD Titik Air) & PBI-10 (Map API/View)
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
│   ├── TitikSumberAirSeeder.php
│   └── PengumumanSeeder.php
├── titik_sumber_air.sql
├── pengumuman.sql
└── dummy_data.sql

resources/views/
├── layouts/
│   └── app.blade.php                      # Layout utama dengan Tailwind CSS & Leaflet CDN
├── water-points/
│   ├── index.blade.php                    # Tabel Admin CRUD Titik Air (PBI-01)
│   ├── create.blade.php                   # Form Tambah Titik + Interactive Map Picker
│   ├── edit.blade.php                     # Form Edit Titik + Interactive Map Picker
│   └── map.blade.php                      # Peta Sebaran Marker Hijau/Kuning/Merah (PBI-10)
└── announcements/
    ├── index.blade.php                    # Tabel Admin Kelola Konten (PBI-12)
    ├── create.blade.php                   # Form Terbit Konten Baru
    ├── edit.blade.php                     # Form Edit Konten
    └── warga.blade.php                    # Portal Edukasi & Pengumuman untuk Warga

routes/
└── water_and_education.php                # Rute Web & REST API siap pakai
```

---

## 🚀 Cara Registrasi & Menjalankan di Laravel

### 1. Hubungkan Rute ke Proyek Utama
Cukup tambahkan baris ini di bagian bawah file `routes/web.php`:
```php
require base_path('routes/water_and_education.php');
```

### 2. Jalankan Migrasi & Seeder Database
```bash
php artisan migrate
php artisan db:seed --class=TitikSumberAirSeeder
php artisan db:seed --class=PengumumanSeeder
```

### 3. Akses Halaman Web:
- **Peta Sebaran Titik Air (PBI-10)**: `http://localhost:8000/peta`
- **Portal Edukasi Warga (PBI-12)**: `http://localhost:8000/edukasi`
- **Admin Kelola Titik Air (PBI-01)**: `http://localhost:8000/admin/water-points`
- **Admin Kelola Pengumuman (PBI-12)**: `http://localhost:8000/admin/announcements`

