<?php

namespace Database\Seeders;

use App\Models\Administration\AuthorizedCategory;
use Illuminate\Database\Seeder;

class CCODESeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AuthorizedCategory::where('code','CCODE')->delete();
        $category = 'CCODE';
        $ccodes = array(
            ['authorized_value'=>'FIC','description'=>'Fiction'],
            ['authorized_value'=>'GRAPHICNOV','description'=>'Graphic Novels']
        );
        foreach($ccodes as $c){
            $authorizedCategory= (new AuthorizedCategory())->getOrCreate($category);
            $authorizedCategory->values()->create([
                'value' => $c['authorized_value'],
                'description' => $c['description']
            ]);
        }
    }
}
