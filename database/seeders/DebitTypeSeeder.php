<?php

namespace Database\Seeders;

use App\Models\Pos\DebitType;
use Illuminate\Database\Seeder;

class DebitTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DebitType::query()->delete();
        $data = array(
            ['code'=>'BOOKBAG','description'=>'Friends of the Library Bookbags','default_amount'=>100000],
            ['code'=>'BOOKSALE','description'=>'Booksale','default_amount'=>60000],
            ['code'=>'COPY','description'=>'Copies','default_amount'=>20000],
            ['code'=>'DAMAGED','description'=>'Damaged','default_amount'=>50000],
            ['code'=>'EARBUDS','description'=>'Earbuds','default_amount'=>150000],
            ['code'=>'MANUAL','description'=>'Manual fee','default_amount'=>200000],
            ['code'=>'Copier Fees','description'=>'Copier Fees','default_amount'=>10000],
        );
        foreach($data as $d){
            DebitType::insert([...$d,
                'can_be_sold'=>true,
                'library_id'=>null
            ]);
        }
    }
}
