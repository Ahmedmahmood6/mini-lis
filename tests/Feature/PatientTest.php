<?php

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('receptionist can view patients list', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    Patient::factory()->count(3)->create();

    $response = $this->actingAs($receptionist)->get(route('reception.patients.index'));

    $response->assertStatus(200);
});

test('receptionist can create a patient', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);

    $response = $this->actingAs($receptionist)->post(route('reception.patients.store'), [
        'name' => 'Ali Hassan',
        'phone' => '01012345678',
        'gender' => 'male',
        'age' => 30,
        'national_id' => '29501011200999',
        'address' => 'Cairo, Egypt',
    ]);

    $response->assertRedirect(route('reception.patients.index'));
    $this->assertDatabaseHas('patients', [
        'name' => 'Ali Hassan',
        'phone' => '01012345678',
    ]);
});

test('receptionist can update a patient', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    $patient = Patient::factory()->create();

    $response = $this->actingAs($receptionist)->put(route('reception.patients.update', $patient), [
        'name' => 'Updated Patient Name',
        'phone' => $patient->phone,
        'gender' => 'female',
        'age' => 25,
    ]);

    $response->assertRedirect(route('reception.patients.index'));
    $this->assertDatabaseHas('patients', [
        'id' => $patient->id,
        'name' => 'Updated Patient Name',
        'gender' => 'female',
    ]);
});

test('receptionist can search patients', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    $patient = Patient::factory()->create(['name' => 'UniqueSearchableName', 'phone' => '01999999999']);

    $response = $this->actingAs($receptionist)->get(route('reception.patients.search', ['q' => 'UniqueSearchable']));

    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'UniqueSearchableName']);
});

test('admin can delete a patient', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $patient = Patient::factory()->create();

    $response = $this->actingAs($admin)->delete(route('reception.patients.destroy', $patient));

    $response->assertRedirect(route('reception.patients.index'));
    $this->assertDatabaseMissing('patients', ['id' => $patient->id]);
});

test('receptionist cannot delete a patient', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    $patient = Patient::factory()->create();

    $response = $this->actingAs($receptionist)->delete(route('reception.patients.destroy', $patient));

    $response->assertStatus(403);
    $this->assertDatabaseHas('patients', ['id' => $patient->id]);
});
