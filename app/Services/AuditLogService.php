<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk mencatat log aktivitas sistem.
 *
 * Fungsi internal createAuditLog — bukan endpoint publik.
 * Dipanggil oleh modul: FD-03, PBI-07, PBI-08, PBI-18.
 *
 * CARA PENGGUNAAN oleh PIC modul lain:
 *
 *   use App\Services\AuditLogService;
 *
 *   // Di dalam controller/service setelah proses bisnis berhasil:
 *   AuditLogService::createAuditLog(
 *       user_id: auth()->id(),       // ID pengguna yang melakukan aksi
 *       aksi: 'create',              // Jenis aksi: create, update, delete, verify, reject, assign, status_change
 *       entitas: 'users',            // Nama tabel/entitas yang terdampak
 *       entitas_id: $user->id        // ID record yang terdampak
 *   );
 *
 * CATATAN PENTING:
 * - user_id HARUS diambil dari auth()->id(), bukan dari input frontend.
 * - Waktu pencatatan dibuat otomatis oleh server.
 * - Panggil fungsi ini di dalam DB::transaction() bersama proses bisnis utama
 *   agar log dan proses bisnis bersifat atomik.
 *
 * CONTOH INTEGRASI DENGAN TRANSAKSI:
 *
 *   use Illuminate\Support\Facades\DB;
 *   use App\Services\AuditLogService;
 *
 *   DB::transaction(function () use ($data) {
 *       // Proses bisnis utama
 *       $user = User::create($data);
 *
 *       // Catat log aktivitas
 *       AuditLogService::createAuditLog(
 *           user_id: auth()->id(),
 *           aksi: 'create',
 *           entitas: 'users',
 *           entitas_id: $user->id
 *       );
 *   });
 */
class AuditLogService
{
    /**
     * Mencatat log aktivitas penting ke database.
     *
     * @param int    $user_id    ID pengguna yang melakukan aksi (dari auth()->id())
     * @param string $aksi       Jenis aksi (create, update, delete, verify, reject, assign, status_change)
     * @param string $entitas    Nama entitas/tabel yang terdampak (users, reports, water_standards, dll)
     * @param int    $entitas_id ID record yang terdampak
     *
     * @return AuditLog Record log yang berhasil dibuat
     *
     * @throws \Exception Jika pencatatan gagal (agar tidak terjadi aktivitas tanpa jejak audit)
     */
    public static function createAuditLog(
        int $user_id,
        string $aksi,
        string $entitas,
        int $entitas_id
    ): AuditLog {
        try {
            return AuditLog::create([
                'user_id' => $user_id,
                'aksi' => $aksi,
                'entitas' => $entitas,
                'entitas_id' => $entitas_id,
                'waktu' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mencatat audit log', [
                'user_id' => $user_id,
                'aksi' => $aksi,
                'entitas' => $entitas,
                'entitas_id' => $entitas_id,
                'error' => $e->getMessage(),
            ]);

            // Re-throw agar transaksi di-rollback dan proses bisnis tidak berhasil
            // tanpa jejak audit
            throw $e;
        }
    }
}
