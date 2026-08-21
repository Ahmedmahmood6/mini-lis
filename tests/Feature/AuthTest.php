<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create([
        'role' => 'receptionist',
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('/dashboard');
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('receptionist cannot access admin users route', function () {
    $receptionist = User::factory()->create([
        'role' => 'receptionist',
    ]);

    $response = $this->actingAs($receptionist)->get('/admin/users');

    $response->assertStatus(403);
});

test('admin can access admin users route', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($admin)->get('/admin/users');

    $response->assertStatus(200);
});

test('technician can access lab orders queue route', function () {
    $technician = User::factory()->create([
        'role' => 'technician',
    ]);

    $response = $this->actingAs($technician)->get('/lab/orders');

    $response->assertStatus(200);
});

test('users can log out', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/login');
});
