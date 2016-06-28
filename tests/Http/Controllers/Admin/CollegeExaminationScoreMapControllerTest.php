<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CollegeExaminationScoreMapControllerTest extends TestCase
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

    public function testCreateExaminationScoreMapForCollege(){
        //1.获取模版 模版包含本校所有学历对应的考试列表
        $template = $this->college->examinationScoreMapTemplate();
        $degrees = $this->college->degrees()->where('estimatable', true)->get();

        //2. 补全Template
        $score_map = [];
        foreach ($template as $examination){

            $is_multiple_degree = !!$examination['multiple_degree'];
            $key = $examination['id'];
            $score_sections = [];
            $score_sections['multiple_degree'] = $is_multiple_degree;
            foreach ($examination['score_sections'] as $score_section){
                $section = [];
                $section['section'] = $score_section;
                if($is_multiple_degree){
                    //如果是多学历考试 为每个学历设置分数
                    foreach ($degrees as $degree){
                        $section[$degree->id . ":score"] = rand(0, 100);
                    }
                }else{
                    $section['score'] = rand(0, 100);
                }

                $score_sections['score_sections'][] = $section;
            }

            $score_map[$key] = $score_sections;
        }
    }
}
