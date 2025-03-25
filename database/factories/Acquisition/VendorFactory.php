<?php

namespace Database\Factories\Acquisition;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
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
            'email' => fake('vi_VN')->companyEmail(),
            'phone' => fake('vi_VN')->phoneNumber(),
            'address' => fake('vi_VN')->address()
        ];
    }
}
