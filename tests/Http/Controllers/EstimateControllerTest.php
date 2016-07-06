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


    //测试评估
    public function testEstimate(){
        //设置核心概率比例 数量/冲刺概率比例 数量
        $countries = \App\AdministrativeArea::countries()->get();
        $degrees = \App\Degree::estimatable()->get();

        $core_range_setting_obj = new \App\CoreRangeSetting();

        $core_range_setting = $core_range_setting_obj->getSetting()->toArray();

        $selected_country = $countries->filter(function ($item){
            return $item->name =='澳洲';
        })->first();

        $selected_degree = $degrees->filter(function ($item){
            return $item->name == '本科';
        })->first();

        //核心院校比例
        $core_range = '50-80';
        $sprint_range = '80-100';

        $core_count = '5';
        $sprint_count = '5';
        for ($i=0; $i<count($core_range_setting); $i++){
            if($core_range_setting[$i]['country_name'] == $selected_country->name){
                for ($j=0; $j<count($core_range_setting[$i]['degrees']); $j++){
                    if ($core_range_setting[$i]['degrees'][$j]['degree_id']== $selected_degree->id) {
                        $core_range_setting[$i]['degrees'][$j]['core']['range'] = $core_range;
                        $core_range_setting[$i]['degrees'][$j]['core']['count'] = $core_count;
                        $core_range_setting[$i]['degrees'][$j]['sprint']['range'] = $sprint_range;
                        $core_range_setting[$i]['degrees'][$j]['sprint']['count'] = $sprint_count;
                    }
                }
            }
        }


        //设置冲刺比例
        \App\Setting::set('core_range', $core_range_setting);
        $core_range_setting_obj->reload();
        $this->assertEquals($core_range, $core_range_setting_obj->getCoreRange($selected_country->id, $selected_degree->id));

        $college_names = [
            [
                'chinese_name' => '澳洲国立大学',
                'english_name' => 'The Australian National University',
                'location' => '堪培拉'
            ],[
                'chinese_name' => '堪培拉大学',
                'english_name' => 'University of Canberra',
                'location' => '堪培拉'
            ],[
                'chinese_name' => '澳洲国防学院',
                'english_name' => '	Australian Defence Force Academy',
                'location' => '堪培拉'
            ],[
                'chinese_name' => '查尔斯史都华大学',
                'english_name' => 'Charles Sturt University',
                'location' => '堪培拉'
            ],[
                'chinese_name' => '麦觉理大学',
                'english_name' => 'Macquarie University',
                'location' => '阿米代尔'
            ],[
                'chinese_name' => '新英格兰大学',
                'english_name' => 'University of New England',
                'location' => '阿米代尔'
            ],[
                'chinese_name' => '新南威尔士大学',
                'english_name' => '	University of New South Wales',
                'location' => '阿米代尔'
            ],
        ];

        foreach ($college_names as $college_name){
            $college = new \App\College();
            $college->chinese_name = $college_name['chinese_name'];
            $college->english_name = $college_name['english_name'];

            $location = \App\AdministrativeArea::where('name', $college_name['location'])->first();
            $college->administrative_area_id = $location->id;
            $college->save();

            $college->degrees()->sync($degrees->map(function ($degree){return $degree->id;})->toArray());
        }
    }

    public function testMapReduceEstimateResult(){
        $res = [
            [
                'college_id' => 1,
                'score' => 50
            ],
            [
                'college_id' => 2,
                'score' => 60
            ],
            [
                'college_id' => 3,
                'score' => 70
            ],
            [
                'college_id' => 4,
                'score' => 71
            ],
            [
                'college_id' => 5,
                'score' => 72
            ],
            [
                'college_id' => 6,
                'score' => 73
            ],
            [
                'college_id' => 7,
                'score' => 71
            ],
            [
                'college_id' => 8,
                'score' => 90
            ],
            [
                'college_id' => 9,
                'score' => 92
            ],
            [
                'college_id' => 10,
                'score' => 93
            ],
            [
                'college_id' => 11,
                'score' => 92
            ],
            [
                'college_id' => 12,
                'score' => 92
            ],
            [
                'college_id' => 13,
                'score' => 92
            ],
            [
                'college_id' => 14,
                'score' => 92
            ],
        ];


        //设置核心概率比例 数量/冲刺概率比例 数量
        $countries = \App\AdministrativeArea::countries()->get();
        $degrees = \App\Degree::estimatable()->get();

        $core_range_setting_obj = new \App\CoreRangeSetting();

        $core_range_setting = $core_range_setting_obj->getSetting()->toArray();

        $selected_country = $countries->filter(function ($item){
            return $item->name =='澳洲';
        })->first();

        $selected_degree = $degrees->filter(function ($item){
            return $item->name == '本科';
        })->first();

        //核心院校比例
        $core_range = '70-80';
        $sprint_range = '80-100';

        $core_count = '5';
        $sprint_count = '5';
        for ($i=0; $i<count($core_range_setting); $i++){
            if($core_range_setting[$i]['country_name'] == $selected_country->name){
                for ($j=0; $j<count($core_range_setting[$i]['degrees']); $j++){
                    if ($core_range_setting[$i]['degrees'][$j]['degree_id']== $selected_degree->id) {
                        $core_range_setting[$i]['degrees'][$j]['core']['range'] = $core_range;
                        $core_range_setting[$i]['degrees'][$j]['core']['count'] = $core_count;
                        $core_range_setting[$i]['degrees'][$j]['sprint']['range'] = $sprint_range;
                        $core_range_setting[$i]['degrees'][$j]['sprint']['count'] = $sprint_count;
                    }
                }
            }
        }


        //设置冲刺比例
        \App\Setting::set('core_range', $core_range_setting);
        $core_range_setting_obj->reload();
        $this->assertEquals($core_range, $core_range_setting_obj->getCoreRange($selected_country->id, $selected_degree->id));

        $range_setting = $core_range_setting_obj->getCountryDegreeSetting($selected_country->id, $selected_degree->id);

        //最终得到的数据结构
        $reduce_result = \App\Estimate::reduceScoreResult($res, $range_setting);

        dd($reduce_result);
        $this->assertEquals(intval($core_count), count($reduce_result['core']));
        $this->assertEquals(intval($sprint_count), count($reduce_result['sprint']));
    }
}
