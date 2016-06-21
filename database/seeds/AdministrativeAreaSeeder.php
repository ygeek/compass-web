<?php

use Illuminate\Database\Seeder;

class AdministrativeAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('adminstrative_area') as $root_node){
            $node = App\AdministrativeArea::create($root_node);

            $node->save();
        }
    }
}
