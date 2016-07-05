<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EstimateControllerTest extends TestCase
{
    use DatabaseMigrations;
    
    public function setUp(){
        parent::setUp();
        $this->seed();

        factory(App\College::class)->create()->each(function($college){
            $college->specialities()->saveMany(factory(App\Speciality::class, 5)->make());
        });
    }

    public function testGetStep1(){
        $this->call('GET', '/estimate/step-1');
        $this->assertResponseOk();
        $this->assertViewHas(['countries', 'degrees', 'years', 'speciality_categories']);
    }

    public function testGetStep2(){
        $response = $this->call('GET', '/estimate/step-1');
        $this->assertResponseOk();
        $countries = $response->original->getData()['countries'];
        $degrees = $response->original->getData()['degrees'];
        $years = collect($response->original->getData()['years']);
        $speciality_categories = $response->original->getData()['speciality_categories'];

        $selected_country = $countries->filter(function ($item){
            return $item->name =='澳洲';
        })->first();

        $selected_degree = $degrees->filter(function ($item){
           return $item->name == '本科';
        })->first();

        $selected_year = $years->random();

        $selected_speciality = $speciality_categories->filter(function ($item){
            return $item->specialities->count() > 0;
        })->random()->specialities->random();

        $params = [
            'selected_country_id' => $selected_country->id,
            'selected_degree_id' => $selected_degree->id,
            'selected_year' => $selected_year,
            'selected_speciality_id' => $selected_speciality->id
        ];

        $this->call('GET', route('estimate.step_second'), $params);

        $this->assertResponseOk();
    }

    public function testResult(){
        
    }
}
