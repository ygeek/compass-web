<?php

use App\AdministrativeArea;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdministrativeAreaTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateAdminstrativeAreaTree()
    {

        $adminstrative_areas = config('adminstrative_area');
        foreach ($adminstrative_areas as $root_node){
            $node = AdministrativeArea::create($root_node);

            $node->save();
            $this->assertTrue($node->isRoot());
        }
    }
}
