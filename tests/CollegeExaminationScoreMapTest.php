<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CollegeExaminationScoreMapTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateMap(){
        $college = factory(\App\College::class)->create();

        $examination = factory(\App\Examination::class)->create();

        $map = new \App\CollegeExaminationScoreMap();

        $map->college_id = $college->id;

        $map->map = [
          [
            'examination_id' => $examination->id,
            'score_sections' => [
              [
                  'section' => '北京:>=500',
                  'score' => 100
              ],
              [
                'section' => '北京:<500',
                'score' => 50
              ]
            ]
          ]  
        ];

        $map->save();
        $this->seeInDatabase('college_examination_score_maps', ['college_id' => $college->id]);
    }
}
