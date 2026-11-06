<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitikSumberAir extends Model
{
    use HasFactory;

    protected $table = 'titik_sumber_air';
    protected $primaryKey = 'titik_air_id';

    protected $fillable = [
        'kode_titik',
        'nama',
        'jenis',
        'alamat',
        'wilayah',
        'latitude',
        'longitude',
        'foto',
        'status_terkini',
        'pengukuran_terakhir',
    ];

    protected $casts = [
        'pengukuran_terakhir' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function laporan()
    {
        return $this->hasMany(LaporanMasalahAir::class, 'titik_air_id');
    }

    public function pengukuran()
    {
        return $this->hasMany(PengukuranKualitasAir::class, 'titik_air_id');
    }
}

