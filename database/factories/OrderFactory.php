<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => 'ORD-'.fake()->unique()->numerify('20260820-####'),
            'patient_id' => Patient::factory(),
            'status' => 'pending',
            'notes' => fake()->sentence(),
            'created_by' => User::factory()->create(['role' => 'receptionist']),
        ];
    }
}
