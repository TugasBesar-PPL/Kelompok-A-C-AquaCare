<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    /**
     * Tabel yang digunakan oleh model ini.
     */
    protected $table = 'audit_logs';

    /**
     * Model ini tidak menggunakan timestamps Laravel default (created_at, updated_at).
     * Waktu pencatatan menggunakan kolom 'waktu'.
     */
    public $timestamps = false;

    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $fillable = [
        'user_id',
        'aksi',
        'entitas',
        'entitas_id',
        'waktu',
    ];

    /**
     * Cast atribut ke tipe data yang sesuai.
     */
    protected function casts(): array
    {
        return [
            'waktu' => 'datetime',
            'entitas_id' => 'integer',
        ];
    }

    /**
     * Relasi ke model User (pelaku aktivitas).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
