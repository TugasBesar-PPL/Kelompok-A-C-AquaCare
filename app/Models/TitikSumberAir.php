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
        'latitude' => 'float',
        'longitude' => 'float',
        'pengukuran_terakhir' => 'datetime',
    ];

    public function laporan()
    {
        return $this->hasMany(LaporanMasalahAir::class, 'titik_air_id', 'titik_air_id');
    }
}
