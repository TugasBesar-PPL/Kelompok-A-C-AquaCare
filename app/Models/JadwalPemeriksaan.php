<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pemeriksaan';
    protected $primaryKey = 'jadwal_id';

    protected $fillable = [
        'titik_air_id',
        'petugas_id',
        'tanggal_jadwal',
        'durasi_menit',
        'status_pemeriksaan',
        'catatan',
    ];

    protected $casts = [
        'tanggal_jadwal' => 'datetime',
        'durasi_menit' => 'integer',
    ];

    public function titikAir()
    {
        return $this->belongsTo(TitikSumberAir::class, 'titik_air_id', 'titik_air_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    /**
     * Waktu berakhir jadwal berdasarkan durasi menit
     */
    public function getWaktuSelesaiAttribute(): Carbon
    {
        return $this->tanggal_jadwal->copy()->addMinutes($this->durasi_menit);
    }

    /**
     * Cek apakah berbenturan dengan waktu yang diberikan
     */
    public function isOverlapping(Carbon $otherStart, Carbon $otherEnd): bool
    {
        $start = $this->tanggal_jadwal;
        $end = $this->waktu_selesai;

        return $otherStart->lt($end) && $otherEnd->gt($start);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status_pemeriksaan) {
            'dijadwalkan' => 'bg-blue-100 text-blue-800 border-blue-200',
            'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'dibatalkan' => 'bg-rose-100 text-rose-800 border-rose-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }
}
