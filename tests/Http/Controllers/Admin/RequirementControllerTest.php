<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RequirementControllerTest extends TestCase
{
    use DatabaseMigrations;
    
    public function setUp()
    {
        parent::setUp();
        $this->seed();
        $this->adminLogin();

        $this->college = factory(\App\College::class)->create();
        $this->college->degrees()->sync(\App\Degree::estimatable()->get()->map(function($item){
            return $item->id;
        })->toArray());
    }

    public function testCreateRequirementForCollege(){
        $this->assertTrue(is_null($this->college->requirement));
        
        $this->call('GET', route('admin.requirement.index', ['type' => \App\College::class, 'id' => $this->college->id]));
        $this->assertResponseOk();
    }
}
