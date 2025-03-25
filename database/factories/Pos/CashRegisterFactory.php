<?php

namespace Database\Factories\Pos;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CashRegister>
 */
class CashRegisterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'description' => fake()->sentence(rand(0,5)),
            'initial_amount' => fake()->randomElement([500000,750000,1000000])
        ];
    }
}
