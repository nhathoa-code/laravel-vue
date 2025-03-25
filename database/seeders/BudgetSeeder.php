<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Sequence;
use App\Models\Acquisition\Budget;
use App\Models\Acquisition\BudgetFund;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Budget::query()->delete(); $index = 0;
        Budget::factory()->count(5)
            ->state(new Sequence(
                ['name' => 'Adult Budget'],
                ['name' => 'Children Budget'],
                ['name' => 'Novel Budget'],
                ['name' => 'Science Budget'],
                ['name' => 'Test Budget'],
            )) 
            ->afterCreating(function (Budget $budget) use(&$index){
                $funds = [
                    ['code' => 'ADF', 'name' => 'Adult Books Fund'],
                    ['code' => 'CDNF', 'name' => 'Children Books Fund'],
                    ['code' => 'NOLF', 'name' => 'Novel Books Fund'],
                    ['code' => 'SCEF', 'name' => 'Science Books Fund'],
                    ['code' => 'TF', 'name' => 'Test Fund'],
                ];
                BudgetFund::factory()
                ->create([
                    'budget_id' => $budget->id,
                    'code' => $funds[$index]['code'],
                    'name' => $funds[$index]['name'],
                    'amount' => $budget->total_amount,
                ]);
                $index++;
            })
            ->create();
    }
}
