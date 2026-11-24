<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanMasalahAir extends Model
{
    use HasFactory;

    protected $table = 'laporan_masalah_air';
    protected $primaryKey = 'laporan_id';

    protected $fillable = [
        'kode_laporan',
        'user_id',
        'titik_air_id',
        'kategori_masalah',
        'wilayah',
        'lokasi',
        'latitude',
        'longitude',
        'deskripsi',
        'foto_laporan',
        'tanggal_laporan',
        'status_laporan',
        'prioritas',
        'admin_verifikator_id',
        'petugas_id',
        'catatan_verifikasi',
        'tanggal_ditugaskan',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_laporan' => 'datetime',
        'tanggal_ditugaskan' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function titikAir()
    {
        return $this->belongsTo(TitikSumberAir::class, 'titik_air_id', 'titik_air_id');
    }

    public function adminVerifikator()
    {
        return $this->belongsTo(User::class, 'admin_verifikator_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
