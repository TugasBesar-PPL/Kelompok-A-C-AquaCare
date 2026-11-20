<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanMasalahAir extends Model
{
    use HasFactory;

    protected $table = 'laporan_masalah_air';

    protected $primaryKey = 'laporan_id';

    protected $fillable = [
        'user_id',
        'titik_air_id',
        'kategori_masalah',
        'wilayah',
        'deskripsi',
        'lokasi',
        'foto_laporan',
        'tanggal_laporan',
        'status_laporan',
    ];

    protected function casts(): array
    {
        return [
            'lokasi' => 'array',
            'tanggal_laporan' => 'datetime',
            'tanggal_ditugaskan' => 'datetime',
            'tanggal_selesai' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
