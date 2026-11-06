<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';
    protected $primaryKey = 'pengumuman_id';

    protected $fillable = [
        'admin_id',
        'judul',
        'isi',
        'kategori',
        'gambar',
        'tanggal_publikasi',
        'status_publikasi',
    ];

    protected $casts = [
        'tanggal_publikasi' => 'datetime',
    ];

    /**
     * Scope untuk konten yang sudah terbit / dipublikasikan untuk warga
     */
    public function scopePublished($query)
    {
        return $query->where('status_publikasi', 'published');
    }

    /**
     * Scope untuk artikel edukasi saja
     */
    public function scopeEdukasi($query)
    {
        return $query->where('kategori', 'edukasi');
    }

    /**
     * Scope untuk pengumuman umum
     */
    public function scopePengumumanUmum($query)
    {
        return $query->where('kategori', 'pengumuman');
    }
}

