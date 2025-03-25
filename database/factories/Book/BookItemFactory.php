<?php

namespace Database\Factories\Book;

use App\Models\Administration\Library;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookItem>
 */
class BookItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $library = Library::inRandomOrder()->first();
        return [
            'barcode' => fake()->unique()->isbn10(),
            'date_acquired' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'status' => fake()->randomElement(['available', 'borrowed', 'lost', 'reserved']),
            'home_library' => $library,
            'current_location' => $library
        ];
    }
}
