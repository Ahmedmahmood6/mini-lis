<?php

namespace Database\Factories;

use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Test>
 */
class TestFactory extends Factory
{
    protected $model = Test::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->lexify('???')),
            'name' => fake()->words(3, true),
            'category' => fake()->randomElement(['Hematology', 'Biochemistry', 'Microbiology']),
            'unit' => 'mg/dL',
            'reference_range' => '70 - 99 mg/dL',
            'price' => fake()->randomFloat(2, 50, 500),
            'is_active' => true,
        ];
    }
}
