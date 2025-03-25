<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\Pos\CashRegister;
use App\Models\Administration\Library;
use Illuminate\Database\Seeder;

class CashRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CashRegister::query()->delete();
        $libraries = Library::all();
        $libraries->each(function($l){
            CashRegister::factory()
            ->state(new Sequence(
                ['description' => 'at the circ desk'],
                ['description' => 'at the ref desk'],
                ['description' => 'at the rectangle desk'],
                ['description' => 'at the north desk'],
                ['description' => 'at the sun desk']
            ))
            ->state([
                'name' => $l->name . ' Register'
            ])
            ->for($l,'toLibrary')
            ->create();
        });
    }
}
