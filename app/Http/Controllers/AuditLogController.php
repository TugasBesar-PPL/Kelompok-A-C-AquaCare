<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Tampilkan halaman Log Aktivitas (Blade view).
     * Hanya admin yang dapat mengakses.
     */
    public function index(Request $request)
    {
        // Query builder dengan eager loading relasi user
        $query = AuditLog::with('user')->orderBy('waktu', 'desc');

        // Filter berdasarkan tanggal awal
        if ($request->filled('from')) {
            $query->whereDate('waktu', '>=', $request->input('from'));
        }

        // Filter berdasarkan tanggal akhir
        if ($request->filled('to')) {
            $query->whereDate('waktu', '<=', $request->input('to'));
        }

        // Filter berdasarkan user_id
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Filter berdasarkan jenis aksi
        if ($request->filled('aksi')) {
            $query->where('aksi', $request->input('aksi'));
        }

        // Pagination 15 item per halaman
        $logs = $query->paginate(15)->withQueryString();

        // Ambil daftar user untuk dropdown filter
        $users = User::select('id', 'name', 'role')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Daftar aksi unik yang sudah tercatat untuk dropdown filter
        $aksiList = AuditLog::select('aksi')
            ->distinct()
            ->orderBy('aksi')
            ->pluck('aksi');

        return view('admin.audit-logs.index', compact('logs', 'users', 'aksiList'));
    }

    /**
     * API endpoint GET /audit-logs
     * Mengembalikan data log dalam format JSON.
     * Hanya admin yang dapat mengakses.
     */
    public function apiIndex(Request $request)
    {
        $query = AuditLog::with('user:id,name')->orderBy('waktu', 'desc');

        if ($request->filled('from')) {
            $query->whereDate('waktu', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('waktu', '<=', $request->input('to'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('aksi')) {
            $query->where('aksi', $request->input('aksi'));
        }

        $logs = $query->paginate(15)->withQueryString();

        // Transform data sesuai kontrak PRD
        $logs->getCollection()->transform(function ($log) {
            return [
                'id' => $log->id,
                'user' => $log->user ? [
                    'id' => $log->user->id,
                    'name' => $log->user->name,
                ] : null,
                'aksi' => $log->aksi,
                'entitas' => $log->entitas,
                'entitas_id' => $log->entitas_id,
                'waktu' => $log->waktu->toIso8601String(),
            ];
        });

        return response()->json($logs);
    }
}
