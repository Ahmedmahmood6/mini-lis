<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Patient;
use App\Models\Result;
use App\Models\Test;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $receptionist = User::where('role', 'receptionist')->first();
        $technician = User::where('role', 'technician')->first();

        $patient1 = Patient::where('phone', '01011112222')->first();
        $patient2 = Patient::where('phone', '01122223333')->first();

        $cbc = Test::where('code', 'CBC')->first();
        $fbs = Test::where('code', 'FBS')->first();
        $creat = Test::where('code', 'CREAT')->first();
        $alt = Test::where('code', 'ALT')->first();

        // --- ORDER 1: In Progress ---
        $order1 = Order::create([
            'order_number' => 'ORD-'.now()->format('Ymd').'-0001',
            'patient_id' => $patient1->id,
            'status' => 'in_progress',
            'notes' => 'Fasting blood sample collected at 8:00 AM.',
            'created_by' => $receptionist->id,
        ]);

        $item1_1 = OrderItem::create([
            'order_id' => $order1->id,
            'test_id' => $cbc->id,
            'price' => $cbc->price,
        ]);

        $item1_2 = OrderItem::create([
            'order_id' => $order1->id,
            'test_id' => $fbs->id,
            'price' => $fbs->price,
        ]);

        Invoice::create([
            'invoice_number' => 'INV-'.now()->format('Ymd').'-0001',
            'order_id' => $order1->id,
            'total_amount' => 200.00,
            'discount' => 0.00,
            'net_amount' => 200.00,
            'paid_amount' => 200.00,
            'payment_status' => 'paid',
            'payment_method' => 'cash',
        ]);

        Result::create([
            'order_item_id' => $item1_1->id,
            'result_text' => null,
            'reference_range' => $cbc->reference_range,
            'status' => 'pending',
        ]);

        Result::create([
            'order_item_id' => $item1_2->id,
            'result_text' => '92 mg/dL',
            'reference_range' => $fbs->reference_range,
            'status' => 'entered',
            'technician_id' => $technician->id,
            'notes' => 'Normal fasting blood sugar level.',
        ]);

        // --- ORDER 2: Completed ---
        $order2 = Order::create([
            'order_number' => 'ORD-'.now()->format('Ymd').'-0002',
            'patient_id' => $patient2->id,
            'status' => 'completed',
            'notes' => 'Routine kidney & liver checkup.',
            'created_by' => $receptionist->id,
        ]);

        $item2_1 = OrderItem::create([
            'order_id' => $order2->id,
            'test_id' => $creat->id,
            'price' => $creat->price,
        ]);

        $item2_2 = OrderItem::create([
            'order_id' => $order2->id,
            'test_id' => $alt->id,
            'price' => $alt->price,
        ]);

        Invoice::create([
            'invoice_number' => 'INV-'.now()->format('Ymd').'-0002',
            'order_id' => $order2->id,
            'total_amount' => 250.00,
            'discount' => 20.00,
            'net_amount' => 230.00,
            'paid_amount' => 230.00,
            'payment_status' => 'paid',
            'payment_method' => 'card',
        ]);

        Result::create([
            'order_item_id' => $item2_1->id,
            'result_text' => '0.9 mg/dL',
            'reference_range' => $creat->reference_range,
            'status' => 'verified',
            'technician_id' => $technician->id,
        ]);

        Result::create([
            'order_item_id' => $item2_2->id,
            'result_text' => '25 U/L',
            'reference_range' => $alt->reference_range,
            'status' => 'verified',
            'technician_id' => $technician->id,
        ]);
    }
}
