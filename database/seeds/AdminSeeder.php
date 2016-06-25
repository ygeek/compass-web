<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Admin::create([
            'username' => 'chareice',
            'name' => 'Chareice',
            'password' => Hash::make('123456')
        ]);
    }
}
