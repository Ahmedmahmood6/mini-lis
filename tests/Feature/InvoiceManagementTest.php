<?php

use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('receptionist can view invoices list', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);

    $response = $this->actingAs($receptionist)->get(route('reception.invoices.index'));

    $response->assertStatus(200);
});

test('receptionist can mark invoice as paid', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    $order = Order::factory()->create();
    $test = Test::factory()->create(['price' => 150.00]);
    OrderItem::create(['order_id' => $order->id, 'test_id' => $test->id, 'price' => 150.00]);

    $invoice = Invoice::create([
        'invoice_number' => 'INV-20260820-9999',
        'order_id' => $order->id,
        'total_amount' => 150.00,
        'discount' => 0.00,
        'net_amount' => 150.00,
        'paid_amount' => 0.00,
        'payment_status' => 'unpaid',
    ]);

    $response = $this->actingAs($receptionist)->patch(route('reception.invoices.pay', $invoice), [
        'payment_method' => 'card',
        'discount' => 10.00,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'total_amount' => 150.00,
        'discount' => 10.00,
        'net_amount' => 140.00,
        'paid_amount' => 140.00,
        'payment_status' => 'paid',
        'payment_method' => 'card',
    ]);
});

test('backend enforces correct total calculations from order items', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    $order = Order::factory()->create();
    $test1 = Test::factory()->create(['price' => 100.00]);
    $test2 = Test::factory()->create(['price' => 200.00]);

    OrderItem::create(['order_id' => $order->id, 'test_id' => $test1->id, 'price' => 100.00]);
    OrderItem::create(['order_id' => $order->id, 'test_id' => $test2->id, 'price' => 200.00]);

    $invoice = Invoice::create([
        'invoice_number' => 'INV-20260820-8888',
        'order_id' => $order->id,
        'total_amount' => 10.00, // Corrupted / wrong total
        'discount' => 0.00,
        'net_amount' => 10.00,
        'paid_amount' => 0.00,
        'payment_status' => 'unpaid',
    ]);

    // Recalculate
    $this->actingAs($receptionist)->patch(route('reception.invoices.recalculate', $invoice));

    // Must recalculate to 300.00 from backend order items
    $this->assertDatabaseHas('invoices', [
        'id' => $invoice->id,
        'total_amount' => 300.00,
        'net_amount' => 300.00,
    ]);
});
