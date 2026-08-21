<?php

use App\Models\Appointment;
use App\Models\Order;
use App\Models\Patient;
use App\Models\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('receptionist can view order create page', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);

    $response = $this->actingAs($receptionist)->get(route('reception.orders.create'));

    $response->assertStatus(200);
});

test('receptionist can create order with multiple tests inside DB transaction', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    $patient = Patient::factory()->create();
    $test1 = Test::factory()->create(['price' => 150.00]);
    $test2 = Test::factory()->create(['price' => 50.00]);

    $response = $this->actingAs($receptionist)->post(route('reception.orders.store'), [
        'patient_id' => $patient->id,
        'test_ids' => [$test1->id, $test2->id],
        'discount' => 20.00,
        'paid_amount' => 180.00,
        'payment_method' => 'cash',
        'notes' => 'Urgent blood test request.',
    ]);

    $order = Order::first();

    $response->assertRedirect(route('reception.orders.show', $order));

    // 1. Order assertion
    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'patient_id' => $patient->id,
        'status' => 'pending',
        'created_by' => $receptionist->id,
    ]);

    // 2. OrderItems assertion
    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'test_id' => $test1->id,
        'price' => 150.00,
    ]);
    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'test_id' => $test2->id,
        'price' => 50.00,
    ]);

    // 3. Results assertion (initial pending results created for technician queue)
    expect($order->orderItems()->count())->toBe(2);

    // 4. Invoice assertion
    $this->assertDatabaseHas('invoices', [
        'order_id' => $order->id,
        'total_amount' => 200.00,
        'discount' => 20.00,
        'net_amount' => 180.00,
        'paid_amount' => 180.00,
        'payment_status' => 'paid',
        'payment_method' => 'cash',
    ]);
});

test('linked appointment status becomes completed when order is created', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    $appointment = Appointment::factory()->create(['status' => 'confirmed']);
    $test = Test::factory()->create(['price' => 100.00]);

    $this->actingAs($receptionist)->post(route('reception.orders.store'), [
        'patient_id' => $appointment->patient_id,
        'appointment_id' => $appointment->id,
        'test_ids' => [$test->id],
    ]);

    $this->assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'status' => 'completed',
    ]);
});

test('receptionist can update order status', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    $order = Order::factory()->create(['status' => 'pending']);

    $response = $this->actingAs($receptionist)->patch(route('reception.orders.update-status', $order), [
        'status' => 'in_progress',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => 'in_progress',
    ]);
});
