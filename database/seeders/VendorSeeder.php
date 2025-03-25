<?php

namespace Database\Seeders;

use App\Models\Acquisition\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vendor::query()->delete();
        Vendor::factory()->count(5)->create();
    }
}
