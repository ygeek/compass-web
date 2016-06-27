<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExaminationScoreWeightTest extends TestCase
{
    use DatabaseMigrations;

    //测试创建得分权重
    public function testCreateScoreWeight(){
        $this->seed();

        $country = \App\AdministrativeArea::countries()->first();
        $degree = \App\Degree::where('name', '本科')->first();
        $examination_map = \App\CountryDegreeExaminationMap::getExaminationsWith($country->name, $degree->name);
        $weight = new \App\ExaminationScoreWeight();

        $weight->weights = collect($examination_map)->map(function($examination) use($examination_map){
            $examination['weight'] = 1 / count($examination_map);
            return $examination;
        });
        $weight->country_id = $country->id;
        $weight->degree_id = $degree->id;
        $weight->save();

        $this->seeInDatabase('examination_score_weights', ['id' => 1]);
    }
}
