<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\College;

class CollegeTest extends TestCase
{
    use DatabaseMigrations;

    public function testPolymorphicRelationForRegionId(){
        $city = factory(App\City::class)->create();
        $state = factory(App\State::class)->create();

        $college = new College();
        $college->localizable()->associate($city);
        $college->chinese_name = '蓝翔技工学校';
        $college->english_name = 'bule shit';
        $this->assertTrue($college->save());
        $this->seeInDatabase('colleges', ['localizable_type' => App\City::class, 'localizable_id' => $city->id]);

        $college = new College();
        $college->localizable()->associate($state);
        $college->chinese_name = '蓝翔技工学校1';
        $college->english_name = 'bule shit1';
        $this->assertTrue($college->save());
        $this->seeInDatabase('colleges', ['localizable_type' => App\State::class, 'localizable_id' => $state->id]);
    }


}
