<?php

use Illuminate\Database\Seeder;

class ExaminationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $examinations = collect(config('examinations'));
        $examinations->each(function($item){
            App\Examination::create($item);
        });
    }
}
