<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(AdministrativeAreaSeeder::class);
         $this->call(DegreeSeeder::class);
         $this->call(ExaminationSeeder::class);
    }
}
