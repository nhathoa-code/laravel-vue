<?php

namespace Database\Seeders;

use App\Models\User\UserMeta;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->delete();
        User::factory()->create([
            'name' => 'Test User',
            'username' => 'nhathoa',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
        // User::factory()->has(UserMeta::factory())
        //     ->state([
        //         'role' => 'staff',
        //     ])
        //     ->create();
        User::factory()->has(UserMeta::factory()
            ->state(['card_number' => '111'])
        )
        ->state([
            'name' => 'Test patron',
            'username' => 'patron',
            'password' => Hash::make('password'),
            'role' => 'patron',
        ])
        ->create();
        User::factory()->has(UserMeta::factory())
            ->count(10)
            ->state([
                'role' => 'patron',
            ])
            ->create();
    }
}
