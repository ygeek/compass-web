<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExaminationScoreWeightsControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(){
        parent::setUp();
        $this->seed();
        $this->adminLogin();
    }

    public function testGetIndex(){
        $this->call('GET', route('admin.examination_score_weights.index'));
        $this->assertResponseOk();
    }

    //测试创建权重
    public function testCreateExaminationScoreWeight(){
        //获取国家
        $countries = \App\AdministrativeArea::countries()->get();
        //获取学历
        $degrees = App\Degree::where(['name' => '本科'])->get();

        //选定一个国家和一个学历
        $country = $countries->first();
        $degree = $degrees->first();

        //获取国家/学历考试
        $response = $this->call('GET', '/admin/country_degree_examination_map', [
            'country' => $country->name,
            'degree' => $degree->name
        ]);

        $this->assertResponseOk();

        //获取到了Map 设置权重
        $map = $response->getData();

        //前端将此数据Render为一个Table
        $examinations = collect($map->data);
        $weights = $examinations->map(function($examination){
            return [
                'examination_ids' => $examination->ids,
                'weight' => 100 / 3
            ];
        });

        $params = [
            'country_id' => $country->id,
            'degree_id' => $degree->id,
            'weights' => $weights
        ];

        $this->call('POST', route('admin.examination_score_weights.store'), $params);
        $this->seeInDatabase('examination_score_weights', ['country_id' => $country->id]);
    }

    public function testGetCreate(){
        $this->call('GET', route('admin.examination_score_weights.create'));
        $this->assertResponseOk();
    }
}
