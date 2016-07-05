<?php

use App\ScoreMapSection;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CollegeTest extends TestCase
{
    use DatabaseMigrations;

    protected $map;
    protected $weight;
    protected $college;
    protected $examinations;

    public function setUp()
    {
        parent::setUp();
        $this->seed();

        $this->college = factory(\App\College::class)->create();
        $this->college->degrees()->sync(\App\Degree::estimatable()->get()->map(function($item){
            return $item->id;
        })->toArray());

        $country = $this->college->country;

        //1.设置该学校对应学历的考试分数映射表

        //创建映射表
        $degrees = $this->college->degrees;
        $degrees->each(function ($degree) use ($country){
            $this->weight = new \App\ExaminationScoreWeight();
            $this->weight->degree_id = $degree->id;
            $this->weight->country_id = $country->id;

            $weight_template = \App\CountryDegreeExaminationMap::getExaminationsWith($country->name, $degree->name);
            $weights = $weight_template->map(function($item) use ($weight_template){
                $item['weight'] = 100 / count($weight_template);
                return $item;
            });

            $this->weight->weights = $weights->toArray();
            $this->weight->save();

            //关联得分比例映射和学校
            DB::table('college_degree')->where('college_id', $this->college->id)
                ->where('degree_id', $degree->id)->update(['examination_score_weight_id' => $this->weight->id]);
        });


        //创建得分映射
        //获取本学校所有考试 通过学历 国家获取
        $college_examinations = [];
        $degrees = $this->college->degrees()->where('estimatable', true)->get();
        foreach ($degrees as $degree){
            $examinations = \App\CountryDegreeExaminationMap::getExaminationsWith($this->college->country->name, $degree->name);
            foreach ($examinations as $examination){
                array_push($college_examinations, $examination['ids']);
            }
        }

        $college_examinations = \App\Examination::whereIn('id',
            collect($college_examinations)->flatten()->unique()
        )->get()->toArray();

        //设置本校所有考试对应的分数
        $score_map = [];
        foreach ($college_examinations as $college_examination){
            //为每门考试设置规则表
            $is_multiple_degree = !!$college_examination['multiple_degree'];
            $key = $college_examination['id'];
            $score_sections = [];
            $score_sections['multiple_degree'] = $is_multiple_degree;
            foreach ($college_examination['score_sections'] as $score_section){
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

        $this->map = new \App\CollegeExaminationScoreMap();
        $this->map->map = $score_map;

        $this->map->college()->associate($this->college);
        $this->map->save();
    }

    //测试将Map和Weight合并
    public function testMergeMap(){
        $mergedMap = \App\College::mergeMap($this->map->map, $this->weight->weights);

        foreach ($mergedMap as $map){
            $this->assertArrayHasKey('weight', $map);
        }
    }

    //计算学校的最终得分
    public function testCalculateScoreOfACollege(){
        //本科澳洲考生入场

        //获取本科澳洲考生需要填写的考试内容
        $examinations = \App\CountryDegreeExaminationMap::getExaminationsWith('澳洲', '本科');

        $degree = \App\Degree::where('name', '本科')->first();
        $yasi = \App\Examination::where('name', '雅思')->first();
        $gaokao = \App\Examination::where('name', '高考')->first();
        $pingjunchengji = \App\Examination::where('name', '高中平均成绩')->first();

        $studentScore = [
            [
                'examination_id' => $gaokao->id, //高考
                'score' => '北京:414', //50 * 0.2
            ],
            [
                'examination_id' => $yasi->id, //雅思
                'score' => '6.5', //60 * 0.4
            ],
            [
                'examination_id' => $pingjunchengji->id, //平均成绩
                'score' => '112', //80 * 0.4
            ],
        ];


        $weight = $this->college->examinationScoreWeight()->where('college_degree.degree_id', $degree->id)->first();
        $merged_map = \App\College::mergeMap($this->college->examinationScoreMap->map, $weight->weights);

        $carry = 0;
        foreach ($studentScore as $student_score){
            $current_examination = $student_score['examination_id'];
            $current_examination_map = $merged_map[$current_examination];

            $current_examination_score_sections = $current_examination_map['score_sections'];

            //遍历此次考试的Section
            foreach ($current_examination_score_sections as $score_section){
                $score_map_section = new ScoreMapSection($score_section['section']);
                if($score_map_section->matching($student_score['score'])){
                    //分数段查找匹配成功
                    $score_key = 'score';
                    //有多个学历的 匹配当前学历
                    if($current_examination_map['multiple_degree']){
                        $score_key = $degree->id . ":" . $score_key;
                    }
                    $current_section_score = $score_section[$score_key] * $current_examination_map['weight'] / 100;
                    $carry += $current_section_score;
                }
            }
        }

        $this->assertEquals($carry, $this->college->calculateWeightScore($studentScore, $degree));
    }
}
