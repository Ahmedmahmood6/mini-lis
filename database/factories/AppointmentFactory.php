<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_name' => fake()->name(),
            'phone' => fake()->numerify('01#########'),
            'gender' => fake()->randomElement(['male', 'female']),
            'age' => fake()->numberBetween(18, 70),
            'appointment_date' => fake()->dateTimeBetween('now', '+1 week'),
            'status' => 'pending',
            'notes' => fake()->sentence(),
            'patient_id' => Patient::factory(),
        ];
    }
}
