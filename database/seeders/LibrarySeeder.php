<?php

namespace Database\Seeders;

use App\Models\Administration\Library;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class LibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Library::query()->delete();
        Library::factory()
            ->count(5)
            ->state(new Sequence(
                    ['name' => 'VNH Library 1'],
                    ['name' => 'VNH Library 2'],
                    ['name' => 'VNH Library 3'],
                    ['name' => 'VNH Library 4'],
                    ['name' => 'VNH Library 5']
                ))
            ->create();
    }
}
