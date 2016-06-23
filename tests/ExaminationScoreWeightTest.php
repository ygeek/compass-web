<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExaminationScoreWeightTest extends TestCase
{
    use DatabaseMigrations;

    //测试创建得分权重
    public function testCreateScoreWeight(){
        $college = factory(\App\College::class)->create();
        $examination1 = factory(\App\Examination::class)->create();
        $examination2 = factory(\App\Examination::class)->create();

        $weight = new \App\ExaminationScoreWeight();

        $weight->weights = [
            [
                'examination_id' => $examination1->id,
                'weight' => 0.1,
            ],
            [
                'examination_id' => $examination2->id,
                'weight' => 0.9
            ]
        ];

        $weight->save();

        $this->seeInDatabase('examination_score_weights', ['id' => 1]);

        $college->examinationScoreWeight()->associate($weight);
        $college->save();
        $this->assertEquals($weight->id, $college->examination_score_weight_id);
    }
}
