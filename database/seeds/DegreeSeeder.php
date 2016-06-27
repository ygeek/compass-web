<?php

use Illuminate\Database\Seeder;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $educations = collect(config('degrees'));
        $educations->each(function($item){
           \App\Degree::create($item);
        });
    }
}
