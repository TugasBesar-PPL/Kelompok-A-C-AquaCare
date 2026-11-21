<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPenanganan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_penanganan';
    protected $primaryKey = 'penanganan_id';

    protected $fillable = [
        'laporan_id',
        'petugas_id',
        'tanggal_pembaruan',
        'status_penanganan',
        'catatan',
        'foto_bukti',
    ];

    protected $casts = [
        'tanggal_pembaruan' => 'datetime',
    ];

    public function laporan()
    {
        return $this->belongsTo(LaporanMasalahAir::class, 'laporan_id', 'laporan_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}

