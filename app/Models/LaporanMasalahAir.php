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

    public function riwayatPenanganan()
    {
        return $this->hasMany(RiwayatPenanganan::class, 'laporan_id', 'laporan_id');
    }

    public function scopeMenunggu($query)
    {
        return $query->where('status_laporan', 'Menunggu');
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status_laporan) {
            'Menunggu' => 'bg-amber-100 text-amber-800 border-amber-200',
            'Ditugaskan' => 'bg-blue-100 text-blue-800 border-blue-200',
            'Diproses' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
            'Selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'Ditolak' => 'bg-rose-100 text-rose-800 border-rose-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    public function getPrioritasColorAttribute(): string
    {
        return match ($this->prioritas) {
            'Tinggi' => 'bg-red-100 text-red-700 border-red-200',
            'Sedang' => 'bg-orange-100 text-orange-700 border-orange-200',
            'Rendah' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            default => 'bg-slate-100 text-slate-600 border-slate-200',
        };
    }
}

