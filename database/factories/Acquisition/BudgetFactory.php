<?php

namespace Database\Factories\Acquisition;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Budget>
 */
class BudgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_date' => fake()->dateTimeBetween('-2 months','now')->format('Y-m-d'),
            'end_date' => fake()->dateTimeBetween('now','+2 months')->format('Y-m-d'),
            'total_amount' => fake()->randomElement([10000000,2500000,5000000,7500000,15000000,20000000]),
            'active' => fake()->randomElement([true,false]),
            'lock' => fake()->randomElement([true,false])
        ];
    }
}
