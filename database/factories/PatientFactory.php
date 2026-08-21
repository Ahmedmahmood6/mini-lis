<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Patient>
 */
class PatientFactory extends Factory
{
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->numerify('01#########'),
            'gender' => fake()->randomElement(['male', 'female']),
            'age' => fake()->numberBetween(18, 75),
            'national_id' => fake()->unique()->numerify('2#############'),
            'address' => fake()->address(),
        ];
    }
}
