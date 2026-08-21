<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Result;
use App\Models\Test;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('technician can view lab work queue', function () {
    $technician = User::factory()->create(['role' => 'technician']);
    Order::factory()->create(['status' => 'pending']);

    $response = $this->actingAs($technician)->get(route('lab.orders.index'));

    $response->assertStatus(200);
});

test('technician can enter test results for an order', function () {
    $technician = User::factory()->create(['role' => 'technician']);
    $order = Order::factory()->create(['status' => 'pending']);
    $test1 = Test::factory()->create(['name' => 'Complete Blood Count']);
    $test2 = Test::factory()->create(['name' => 'Fasting Blood Sugar']);

    $item1 = OrderItem::create(['order_id' => $order->id, 'test_id' => $test1->id, 'price' => 150.00]);
    $item2 = OrderItem::create(['order_id' => $order->id, 'test_id' => $test2->id, 'price' => 50.00]);

    $result1 = Result::create(['order_item_id' => $item1->id, 'result_text' => null, 'status' => 'pending']);
    $result2 = Result::create(['order_item_id' => $item2->id, 'result_text' => null, 'status' => 'pending']);

    $response = $this->actingAs($technician)->put(route('lab.orders.results.update', $order), [
        'results' => [
            [
                'order_item_id' => $item1->id,
                'result_text' => "WBC = 7.2\nRBC = 4.8\nHGB = 13.5",
                'notes' => 'Normal blood count.',
            ],
            [
                'order_item_id' => $item2->id,
                'result_text' => '95 mg/dL',
                'notes' => 'Fasting glucose level.',
            ],
        ],
    ]);

    $response->assertRedirect(route('lab.orders.index'));

    // Assert results updated
    $this->assertDatabaseHas('results', [
        'order_item_id' => $item1->id,
        'result_text' => "WBC = 7.2\nRBC = 4.8\nHGB = 13.5",
        'status' => 'entered',
        'technician_id' => $technician->id,
    ]);

    $this->assertDatabaseHas('results', [
        'order_item_id' => $item2->id,
        'result_text' => '95 mg/dL',
        'status' => 'entered',
        'technician_id' => $technician->id,
    ]);

    // Assert order status automatically updated to completed because all results entered
    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'status' => 'completed',
    ]);
});

test('receptionist cannot access technician result entry route', function () {
    $receptionist = User::factory()->create(['role' => 'receptionist']);
    $order = Order::factory()->create();

    $response = $this->actingAs($receptionist)->get(route('lab.orders.results.edit', $order));

    $response->assertStatus(403);
});
