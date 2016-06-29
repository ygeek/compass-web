<?php

use Illuminate\Database\Seeder;

class SpecialityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list_of_speciality_category = collect(config('speciality_categories'));
        $list_of_speciality_category->each(function ($item){
            \App\SpecialityCategory::create($item);
        });
    }
}
