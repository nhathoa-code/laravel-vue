<?php

namespace Database\Factories\Administration;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Library>
 */
class LibraryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake('vi_VN')->company(),
            'address' => fake('vi_VN')->address(),
            'contact' => fake('vi_VN')->phoneNumber()
        ];
    }
}
