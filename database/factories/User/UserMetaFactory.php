<?php

namespace Database\Factories\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserMetaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'birthdate' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'gender' => fake()->randomElement(['male', 'female']),
            'phone' => fake('vi_VN')->unique()->phoneNumber(),
            'address' => fake('vi_VN')->address(),
            'card_number' => fake()->unique()->ean8(),
            'registration_date' => fake()->dateTimeBetween('-1 years', 'now')->format('Y-m-d'),
            'expiration_date' => fake()->dateTimeBetween('-5 months', '+6 months')->format('Y-m-d'),
            'status' => 'active'            
        ];
    }
}
