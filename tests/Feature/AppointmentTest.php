<?php

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest patient can view booking page', function () {
    $response = $this->get(route('booking.create'));

    $response->assertStatus(200);
});

test('guest patient can book appointment and auto create patient if phone not found', function () {
    $test = Test::factory()->create();

    $response = $this->post(route('booking.store'), [
        'name' => 'Sara Mahmoud',
        'phone' => '01098765432',
        'gender' => 'female',
        'age' => 26,
        'test_id' => $test->id,
        'preferred_date' => now()->addDay()->format('Y-m-d'),
        'preferred_time' => '10:30',
        'notes' => 'Fasting blood sugar test.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    // Patient created
    $this->assertDatabaseHas('patients', [
        'name' => 'Sara Mahmoud',
        'phone' => '01098765432',
    ]);

    // Appointment created with status pending
    $patient = Patient::where('phone', '01098765432')->first();
    $this->assertDatabaseHas('appointments', [
        'patient_id' => $patient->id,
        'status' => 'pending',
        'test_id' => $test->id,
    ]);
});

test('guest booking uses existing patient if phone matches', function () {
    $existingPatient = Patient::factory()->create(['phone' => '01211112222', 'name' => 'Old Name']);

    $this->post(route('booking.store'), [
        'name' => 'New Name Request',
        'phone' => '01211112222',
        'preferred_date' => now()->addDay()->format('Y-m-d'),
        'preferred_time' => '14:00',
    ]);

    // Patient count should still be 1
    expect(Patient::where('phone', '01211112222')->count())->toBe(1);

    // Appointment created linked to existing patient
    $this->assertDatabaseHas('appointments', [
        'patient_id' => $existingPatient->id,
        'status' => 'pending',
    ]);
});

test('receptionist can confirm appointment', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    $appointment = Appointment::factory()->create(['status' => 'pending']);

    $response = $this->actingAs($receptionist)->patch(route('reception.appointments.confirm', $appointment));

    $response->assertRedirect();
    $this->assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'status' => 'confirmed',
    ]);
});

test('receptionist can cancel appointment with reason', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    $appointment = Appointment::factory()->create(['status' => 'pending']);

    $response = $this->actingAs($receptionist)->patch(route('reception.appointments.cancel', $appointment), [
        'cancellation_reason' => 'Patient called to cancel due to travel.',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'status' => 'cancelled',
        'cancellation_reason' => 'Patient called to cancel due to travel.',
    ]);
});
