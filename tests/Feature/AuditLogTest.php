<?php

use App\Models\AuditLog;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
});

/*
|--------------------------------------------------------------------------
| PBI-17: Log Aktivitas — Feature Tests
|--------------------------------------------------------------------------
*/

// Helper: membuat user dengan role tertentu
function createUser(string $role = 'admin', bool $active = true): User
{
    return User::factory()->create([
        'role' => $role,
        'is_active' => $active,
    ]);
}

// Helper: membuat sample audit log
function createAuditLog(User $user, string $aksi = 'create', string $entitas = 'users', int $entitasId = 1): AuditLog
{
    return AuditLog::create([
        'user_id' => $user->id,
        'aksi' => $aksi,
        'entitas' => $entitas,
        'entitas_id' => $entitasId,
        'waktu' => now(),
    ]);
}

/*
|--------------------------------------------------------------------------
| Akses Halaman Log Aktivitas
|--------------------------------------------------------------------------
*/

test('admin berhasil membuka halaman log aktivitas', function () {
    $admin = createUser('admin');

    $response = $this->actingAs($admin)->get('/admin/audit-logs');

    $response->assertStatus(200);
    $response->assertViewIs('admin.audit-logs.index');
    $response->assertSee('Log Aktivitas');
});

test('petugas tidak dapat mengakses halaman log aktivitas', function () {
    $petugas = createUser('petugas');

    $response = $this->actingAs($petugas)->get('/admin/audit-logs');

    $response->assertStatus(403);
});

test('warga tidak dapat mengakses halaman log aktivitas', function () {
    $warga = createUser('warga');

    $response = $this->actingAs($warga)->get('/admin/audit-logs');

    $response->assertStatus(403);
});

test('pengguna tidak login diarahkan ke halaman login', function () {
    $response = $this->get('/admin/audit-logs');

    $response->assertRedirect('/login');
});

/*
|--------------------------------------------------------------------------
| Melihat Daftar Log Aktivitas
|--------------------------------------------------------------------------
*/

test('admin berhasil melihat daftar aktivitas', function () {
    $admin = createUser('admin');
    createAuditLog($admin, 'create', 'users', 5);
    createAuditLog($admin, 'update', 'water_standards', 3);

    $response = $this->actingAs($admin)->get('/admin/audit-logs');

    $response->assertStatus(200);
    $response->assertSee('Create');
    $response->assertSee('Update');
    $response->assertSee('Users');
    $response->assertSee('Water standards');
});

test('halaman menampilkan empty state jika data tidak tersedia', function () {
    $admin = createUser('admin');

    $response = $this->actingAs($admin)->get('/admin/audit-logs');

    $response->assertStatus(200);
    $response->assertSee('Belum ada aktivitas');
});

/*
|--------------------------------------------------------------------------
| Filter Log Aktivitas
|--------------------------------------------------------------------------
*/

test('admin berhasil memfilter berdasarkan tanggal', function () {
    $admin = createUser('admin');

    // Log kemarin
    AuditLog::create([
        'user_id' => $admin->id,
        'aksi' => 'create',
        'entitas' => 'users',
        'entitas_id' => 1,
        'waktu' => now()->subDay(),
    ]);

    // Log hari ini
    AuditLog::create([
        'user_id' => $admin->id,
        'aksi' => 'update',
        'entitas' => 'water_standards',
        'entitas_id' => 2,
        'waktu' => now(),
    ]);

    $response = $this->actingAs($admin)->get('/admin/audit-logs?' . http_build_query([
        'from' => now()->format('Y-m-d'),
        'to' => now()->format('Y-m-d'),
    ]));

    $response->assertStatus(200);
    $response->assertSee('Update');
});

test('admin berhasil memfilter berdasarkan pengguna', function () {
    $admin = createUser('admin');
    $petugas = createUser('petugas');

    createAuditLog($admin, 'create', 'users', 1);
    createAuditLog($petugas, 'status_change', 'reports', 1);

    $response = $this->actingAs($admin)->get('/admin/audit-logs?user_id=' . $petugas->id);

    $response->assertStatus(200);
    $response->assertSee('Status change');
});

test('admin berhasil memfilter berdasarkan jenis aksi', function () {
    $admin = createUser('admin');

    createAuditLog($admin, 'create', 'users', 1);
    createAuditLog($admin, 'verify', 'reports', 2);
    createAuditLog($admin, 'update', 'water_standards', 1);

    $response = $this->actingAs($admin)->get('/admin/audit-logs?aksi=verify');

    $response->assertStatus(200);
    $response->assertSee('Verify');
});

test('admin berhasil melakukan reset filter', function () {
    $admin = createUser('admin');
    createAuditLog($admin, 'create', 'users', 1);

    // Dengan filter yang tidak match
    $response = $this->actingAs($admin)->get('/admin/audit-logs?aksi=delete');
    $response->assertSee('Tidak ada hasil');

    // Reset (tanpa filter)
    $response = $this->actingAs($admin)->get('/admin/audit-logs');
    $response->assertSee('Create');
});

test('empty state ketika hasil filter tidak ditemukan', function () {
    $admin = createUser('admin');
    createAuditLog($admin, 'create', 'users', 1);

    $response = $this->actingAs($admin)->get('/admin/audit-logs?aksi=delete');

    $response->assertStatus(200);
    $response->assertSee('Tidak ada hasil');
    $response->assertSee('Tidak ditemukan log aktivitas');
});

/*
|--------------------------------------------------------------------------
| Pagination
|--------------------------------------------------------------------------
*/

test('pagination berjalan dengan benar', function () {
    $admin = createUser('admin');

    // Buat 20 log (melebihi 15 per halaman)
    for ($i = 1; $i <= 20; $i++) {
        AuditLog::create([
            'user_id' => $admin->id,
            'aksi' => 'create',
            'entitas' => 'users',
            'entitas_id' => $i,
            'waktu' => now()->subMinutes(20 - $i),
        ]);
    }

    // Halaman 1
    $response = $this->actingAs($admin)->get('/admin/audit-logs');
    $response->assertStatus(200);
    $response->assertSee('Menampilkan 1');

    // Halaman 2
    $response = $this->actingAs($admin)->get('/admin/audit-logs?page=2');
    $response->assertStatus(200);
});

/*
|--------------------------------------------------------------------------
| AuditLogService — createAuditLog
|--------------------------------------------------------------------------
*/

test('log berhasil disimpan melalui fungsi internal createAuditLog', function () {
    $admin = createUser('admin');

    $log = AuditLogService::createAuditLog(
        user_id: $admin->id,
        aksi: 'create',
        entitas: 'users',
        entitas_id: 99
    );

    expect($log)->toBeInstanceOf(AuditLog::class);
    expect($log->user_id)->toBe($admin->id);
    expect($log->aksi)->toBe('create');
    expect($log->entitas)->toBe('users');
    expect($log->entitas_id)->toBe(99);

    $this->assertDatabaseHas('audit_logs', [
        'user_id' => $admin->id,
        'aksi' => 'create',
        'entitas' => 'users',
        'entitas_id' => 99,
    ]);
});

test('waktu log tercatat otomatis', function () {
    $admin = createUser('admin');

    $log = AuditLogService::createAuditLog(
        user_id: $admin->id,
        aksi: 'update',
        entitas: 'water_standards',
        entitas_id: 1
    );

    expect($log->waktu)->not->toBeNull();
    expect($log->waktu->diffInSeconds(now()))->toBeLessThan(5);
});

test('createAuditLog mencatat berbagai jenis aksi', function () {
    $admin = createUser('admin');

    $aksiList = ['create', 'update', 'delete', 'verify', 'reject', 'assign', 'status_change'];

    foreach ($aksiList as $aksi) {
        $log = AuditLogService::createAuditLog(
            user_id: $admin->id,
            aksi: $aksi,
            entitas: 'test_entity',
            entitas_id: 1
        );

        expect($log->aksi)->toBe($aksi);
    }

    expect(AuditLog::count())->toBe(count($aksiList));
});

/*
|--------------------------------------------------------------------------
| API Endpoint GET /api/audit-logs
|--------------------------------------------------------------------------
*/

test('api audit-logs mengembalikan JSON untuk admin', function () {
    $admin = createUser('admin');
    createAuditLog($admin, 'create', 'users', 1);

    $response = $this->actingAs($admin)
        ->getJson('/api/audit-logs');

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'user' => ['id', 'name'],
                'aksi',
                'entitas',
                'entitas_id',
                'waktu',
            ],
        ],
    ]);
});

test('api audit-logs ditolak untuk petugas', function () {
    $petugas = createUser('petugas');

    $response = $this->actingAs($petugas)
        ->getJson('/api/audit-logs');

    $response->assertStatus(403);
});

test('api audit-logs ditolak untuk warga', function () {
    $warga = createUser('warga');

    $response = $this->actingAs($warga)
        ->getJson('/api/audit-logs');

    $response->assertStatus(403);
});

test('api audit-logs mendukung filter query parameters', function () {
    $admin = createUser('admin');

    createAuditLog($admin, 'create', 'users', 1);
    createAuditLog($admin, 'verify', 'reports', 2);

    $response = $this->actingAs($admin)
        ->getJson('/api/audit-logs?aksi=verify');

    $response->assertStatus(200);

    $data = $response->json('data');
    expect(count($data))->toBe(1);
    expect($data[0]['aksi'])->toBe('verify');
});
