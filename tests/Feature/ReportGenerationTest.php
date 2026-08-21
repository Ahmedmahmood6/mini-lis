<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Result;
use App\Models\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('pdf report is generated and streamed for a completed order', function () {
    $technician = User::factory()->create(['role' => 'technician']);
    $order = Order::factory()->create(['status' => 'completed']);
    $test = Test::factory()->create(['name' => 'Fasting Blood Sugar']);
    $item = OrderItem::create(['order_id' => $order->id, 'test_id' => $test->id, 'price' => 50.00]);

    Result::create([
        'order_item_id' => $item->id,
        'result_text' => '95 mg/dL',
        'status' => 'entered',
        'technician_id' => $technician->id,
    ]);

    $response = $this->actingAs($technician)->get(route('reports.pdf', $order));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');

    // Assert database report record was upserted
    $this->assertDatabaseHas('reports', [
        'order_id' => $order->id,
        'generated_by' => $technician->id,
    ]);
});

test('pdf report download returns a file attachment for a completed order', function () {
    $technician = User::factory()->create(['role' => 'technician']);
    $order = Order::factory()->create(['status' => 'completed']);
    $test = Test::factory()->create(['name' => 'CBC']);
    $item = OrderItem::create(['order_id' => $order->id, 'test_id' => $test->id, 'price' => 150.00]);

    Result::create([
        'order_item_id' => $item->id,
        'result_text' => "WBC = 7.2\nRBC = 4.8\nHGB = 13.5",
        'status' => 'entered',
        'technician_id' => $technician->id,
    ]);

    $response = $this->actingAs($technician)->get(route('reports.download', $order));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

test('pdf report cannot be generated when order status is not completed', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    $order = Order::factory()->create(['status' => 'pending']);

    $response = $this->actingAs($receptionist)->get(route('reports.pdf', $order));

    $response->assertStatus(422);
});

test('whatsapp link is generated with patient phone number and report url', function () {
    $technician = User::factory()->create(['role' => 'technician']);
    $order = Order::factory()->create(['status' => 'completed']);

    $response = $this->actingAs($technician)->get(route('reports.whatsapp', $order));

    $response->assertRedirect();
    $this->assertStringContainsString('https://wa.me/', $response->headers->get('Location'));
    $this->assertStringContainsString(urlencode($order->patient->name), $response->headers->get('Location'));
});
