<?php

use App\Models\User;
use Database\Seeders\BakuMutuParameterSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(BakuMutuParameterSeeder::class);
});

test('it displays the login page with demo switchers', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('AquaCare')
        ->assertSee('Platform Monitoring & Pelaporan Air Bersih')
        ->assertSee('Masuk ke Sistem');
});

test('it displays the register page for warga', function () {
    $this->get('/register')
        ->assertOk()
        ->assertSee('Daftar Akun Warga')
        ->assertSee('Daftar Sebagai Warga');
});

test('warga can register and is assigned role warga', function () {
    $response = $this->post('/register', [
        'name' => 'Budi Warga',
        'email' => 'budiwarga@example.com',
        'phone' => '081234567890',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect('/warga/lapor');
    $this->assertAuthenticated();

    $user = User::where('email', 'budiwarga@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->role)->toBe('warga')
        ->and($user->is_active)->toBeTrue();
});

test('registration rejects password under 8 characters', function () {
    $response = $this->post('/register', [
        'name' => 'Budi Warga',
        'email' => 'budiwarga@example.com',
        'password' => 'short',
        'password_confirmation' => 'short',
    ]);

    $response->assertSessionHasErrors(['password']);
    $this->assertGuest();
});

test('user can login with valid credentials', function () {
    $user = User::create([
        'name' => 'Admin Arrival',
        'email' => 'arrival@aquacare.id',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'is_active' => true,
    ]);

    $response = $this->post('/login', [
        'email' => 'arrival@aquacare.id',
        'password' => 'password123',
    ]);

    $response->assertRedirect('/admin/water-standards');
    $this->assertAuthenticatedAs($user);
});

test('login fails with wrong password and increments login_gagal', function () {
    $user = User::create([
        'name' => 'Admin Arrival',
        'email' => 'arrival@aquacare.id',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'is_active' => true,
    ]);

    $response = $this->post('/login', [
        'email' => 'arrival@aquacare.id',
        'password' => 'wrongpass',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();

    $user->refresh();
    expect($user->login_gagal)->toBe(1);
});

test('user is locked out after 5 consecutive failed logins', function () {
    $user = User::create([
        'name' => 'Target User',
        'email' => 'target@example.com',
        'password' => Hash::make('password123'),
        'role' => 'warga',
        'is_active' => true,
        'login_gagal' => 4,
    ]);

    $response = $this->post('/login', [
        'email' => 'target@example.com',
        'password' => 'wrongpass',
    ]);

    $response->assertSessionHasErrors(['email']);
    $user->refresh();
    expect($user->terkunci_sampai)->not->toBeNull();
});

test('inactive user cannot login', function () {
    User::create([
        'name' => 'Inactive User',
        'email' => 'inactive@example.com',
        'password' => Hash::make('password123'),
        'role' => 'warga',
        'is_active' => false,
    ]);

    $response = $this->post('/login', [
        'email' => 'inactive@example.com',
        'password' => 'password123',
    ]);

    $response->assertSessionHasErrors(['email']);
    $this->assertGuest();
});

test('user can logout successfully', function () {
    $user = User::create([
        'name' => 'Warga Test',
        'email' => 'warga@example.com',
        'password' => Hash::make('password123'),
        'role' => 'warga',
        'is_active' => true,
    ]);

    $this->actingAs($user)->post('/logout')
        ->assertRedirect('/login');

    $this->assertGuest();
});

test('warga cannot access admin user management', function () {
    $warga = User::create([
        'name' => 'Warga Biasa',
        'email' => 'warga2@example.com',
        'password' => Hash::make('password123'),
        'role' => 'warga',
        'is_active' => true,
    ]);

    $this->actingAs($warga)->get('/admin/users')
        ->assertForbidden();
});

test('admin can access user management', function () {
    $admin = User::create([
        'name' => 'Super Admin',
        'email' => 'admin2@aquacare.id',
        'password' => Hash::make('password123'),
        'role' => 'admin',
        'is_active' => true,
    ]);

    $this->actingAs($admin)->get('/admin/users')
        ->assertOk()
        ->assertSee('Daftar Akun Pengguna Terdaftar');
});
