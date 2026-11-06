<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';
    protected $primaryKey = 'log_id';

    protected $fillable = [
        'user_id',
        'aksi',
        'entitas',
        'entitas_id',
        'keterangan',
        'waktu',
    ];

    protected $casts = [
        'waktu' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper to create audit log as specified in PBI-17 & PBI-07
     */
    public static function createAuditLog($userId, string $aksi, string $entitas, string|int $entitasId, ?string $keterangan = null): self
    {
        return self::create([
            'user_id' => $userId,
            'aksi' => $aksi,
            'entitas' => $entitas,
            'entitas_id' => (string) $entitasId,
            'keterangan' => $keterangan,
            'waktu' => now(),
        ]);
    }
}

