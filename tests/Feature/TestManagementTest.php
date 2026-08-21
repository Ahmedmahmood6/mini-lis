<?php

use App\Models\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view tests catalog list', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Test::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('admin.tests.index'));

    $response->assertStatus(200);
});

test('admin can create a new lab test', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.tests.store'), [
        'code' => 'KFT',
        'name' => 'Kidney Function Test',
        'category' => 'Biochemistry',
        'unit' => 'mg/dL',
        'reference_range' => '0.7 - 1.3 mg/dL',
        'price' => 120.00,
        'is_active' => true,
    ]);

    $response->assertRedirect(route('admin.tests.index'));
    $this->assertDatabaseHas('tests', [
        'code' => 'KFT',
        'name' => 'Kidney Function Test',
        'price' => 120.00,
    ]);
});

test('admin can update a lab test', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $test = Test::factory()->create(['price' => 100.00]);

    $response = $this->actingAs($admin)->put(route('admin.tests.update', $test), [
        'code' => $test->code,
        'name' => 'Updated Test Name',
        'category' => $test->category,
        'price' => 180.00,
    ]);

    $response->assertRedirect(route('admin.tests.index'));
    $this->assertDatabaseHas('tests', [
        'id' => $test->id,
        'name' => 'Updated Test Name',
        'price' => 180.00,
    ]);
});

test('admin can delete a test without order items', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $test = Test::factory()->create();

    $response = $this->actingAs($admin)->delete(route('admin.tests.destroy', $test));

    $response->assertRedirect(route('admin.tests.index'));
    $this->assertDatabaseMissing('tests', ['id' => $test->id]);
});

test('non admin user cannot create tests', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);

    $response = $this->actingAs($receptionist)->get(route('admin.tests.index'));

    $response->assertStatus(403);
});
