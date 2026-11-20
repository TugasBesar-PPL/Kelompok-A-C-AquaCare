<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengukuranKualitasAir extends Model
{
    protected $table = 'pengukuran_kualitas_air';
    protected $primaryKey = 'pengukuran_id';

    protected $fillable = [
        'titik_air_id', 'petugas_id', 'jadwal_id', 'tanggal_pengukuran',
        'ph', 'kekeruhan', 'tds', 'suhu', 'sisa_klor', 'status_kelayakan'
    ];

    protected $casts = [
        'tanggal_pengukuran' => 'datetime',
        'ph' => 'float',
        'kekeruhan' => 'float',
        'tds' => 'float',
        'suhu' => 'float',
        'sisa_klor' => 'float',
    ];

    public function titikSumberAir()
    {
        return $this->belongsTo(TitikSumberAir::class, 'titik_air_id', 'titik_air_id');
    }
}