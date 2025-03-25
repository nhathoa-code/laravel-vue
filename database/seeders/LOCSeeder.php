<?php

namespace Database\Seeders;

use App\Models\Administration\AuthorizedCategory;
use Illuminate\Database\Seeder;

class LOCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AuthorizedCategory::where('code','LOC')->delete();
        $category = 'LOC';
        $locs = array(
            ['authorized_value'=>'ADULT','description'=>'Adult'],
            ['authorized_value'=>'AFIC','description'=>'Adult Fiction'],
            ['authorized_value'=>'CHILD','description'=>'Children\'s Area'],
            ['authorized_value'=>'JUVENILE','description'=>'Juvenile'],
            ['authorized_value'=>'SPC','description'=>'Special Collection'],
            ['authorized_value'=>'TEEN','description'=>'Teen'],
            ['authorized_value'=>'YA','description'=>'Young Adult'],
            ['authorized_value'=>'DISPLAY','description'=>'On Display'],
            ['authorized_value'=>'FIC','description'=>'Fiction'],
            ['authorized_value'=>'GEN','description'=>'General Stacks'],
            ['authorized_value'=>'NEW','description'=>'New Books Shelf'],
            ['authorized_value'=>'PROC','description'=>'Processing Center'],
            ['authorized_value'=>'REF','description'=>'Reference'],
            ['authorized_value'=>'STAFF','description'=>'Staff Office'],
            ['authorized_value'=>'ANONFIC','description'=>'Adult Non-fiction'],
            ['authorized_value'=>'AREF','description'=>'Adult Reference'],
        );
        foreach($locs as $l){
            $authorizedCategory= (new AuthorizedCategory())->getOrCreate($category);
            $authorizedCategory->values()->create([
                'value' => $l['authorized_value'],
                'description' => $l['description']
            ]);
        }
    }
}
