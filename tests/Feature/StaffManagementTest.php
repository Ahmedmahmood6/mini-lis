<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('admin can view staff list', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    $technician = User::factory()->create(['role' => 'technician']);

    $response = $this->actingAs($admin)->get(route('admin.users.index'));

    $response->assertStatus(200);
    $response->assertSee($receptionist->name);
    $response->assertSee($technician->name);
});

test('admin can open create staff page', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.users.create'));

    $response->assertStatus(200);
});

test('admin can create receptionist', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.users.store'), [
        'name' => 'Receptionist Sarah',
        'email' => 'sarah@minilis.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
        'role' => 'receptionist',
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', [
        'name' => 'Receptionist Sarah',
        'email' => 'sarah@minilis.com',
        'role' => 'receptionist',
    ]);
});

test('admin can create technician', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.users.store'), [
        'name' => 'Technician Alex',
        'email' => 'alex@minilis.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
        'role' => 'technician',
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', [
        'name' => 'Technician Alex',
        'email' => 'alex@minilis.com',
        'role' => 'technician',
    ]);
});

test('password is stored as hashed', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->post(route('admin.users.store'), [
        'name' => 'Technician Hashed',
        'email' => 'hashed@minilis.com',
        'password' => 'my-secure-password',
        'password_confirmation' => 'my-secure-password',
        'role' => 'technician',
    ]);

    $user = User::where('email', 'hashed@minilis.com')->first();
    expect($user)->not->toBeNull();
    expect(Hash::check('my-secure-password', $user->password))->toBeTrue();
});

test('receptionist cannot access staff management', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);

    $response = $this->actingAs($receptionist)->get(route('admin.users.index'));

    $response->assertStatus(403);
});

test('technician cannot access staff management', function () {
    $technician = User::factory()->create(['role' => 'technician']);

    $response = $this->actingAs($technician)->get(route('admin.users.index'));

    $response->assertStatus(403);
});

test('guest cannot access staff management', function () {
    $response = $this->get(route('admin.users.index'));

    $response->assertRedirect(route('login'));
});

test('admin can edit receptionist', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $receptionist = User::factory()->create([
        'name' => 'Old Name',
        'role' => 'receptionist',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.users.update', $receptionist), [
        'name' => 'Updated Name',
        'email' => $receptionist->email,
        'role' => 'receptionist',
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', [
        'id' => $receptionist->id,
        'name' => 'Updated Name',
        'role' => 'receptionist',
    ]);
});

test('admin can edit technician', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $technician = User::factory()->create([
        'role' => 'technician',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.users.update', $technician), [
        'name' => 'Technician Upgraded',
        'email' => $technician->email,
        'role' => 'technician',
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', [
        'id' => $technician->id,
        'name' => 'Technician Upgraded',
    ]);
});

test('admin cannot create an admin through the form', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.users.store'), [
        'name' => 'Fake Admin',
        'email' => 'fakeadmin@minilis.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
        'role' => 'admin',
    ]);

    $response->assertSessionHasErrors('role');
    $this->assertDatabaseMissing('users', [
        'email' => 'fakeadmin@minilis.com',
    ]);
});

test('admin cannot delete the currently logged in admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));

    $response->assertStatus(403);
    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});
