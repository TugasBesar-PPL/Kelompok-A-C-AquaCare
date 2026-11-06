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

    /**
     * Scope filter pencarian titik air (PBI-01 & PBI-10)
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['wilayah'] ?? null, function ($q, $wilayah) {
            $q->where('wilayah', $wilayah);
        });

        $query->when($filters['jenis'] ?? null, function ($q, $jenis) {
            $q->where('jenis', $jenis);
        });

        $query->when($filters['status'] ?? null, function ($q, $status) {
            $q->where('status_terkini', $status);
        });

        $query->when($filters['q'] ?? null, function ($q, $keyword) {
            $q->where(function ($sub) use ($keyword) {
                $sub->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('alamat', 'like', "%{$keyword}%");
            });
        });
    }

    /**
     * Relasi ke pengukuran kualitas air (PBI-03 / PBI-04)
     */
    public function pengukuran()
    {
        return $this->hasMany(PengukuranKualitasAir::class, 'titik_air_id', 'titik_air_id');
    }

    /**
     * Cek apakah titik air memiliki riwayat pengukuran
     * Aturan bisnis UC-04 (A2): Titik yang sudah memiliki pengukuran tidak dapat dihapus
     */
    public function hasPengukuran(): bool
    {
        // Cek jika tabel pengukuran_kualitas_air ada dan berisi data terkait
        try {
            return \Illuminate\Support\Facades\DB::table('pengukuran_kualitas_air')
                ->where('titik_air_id', $this->titik_air_id)
                ->exists();
        } catch (\Exception $e) {
            return false;
        }
    }
}

