<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IntentionsControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->seed();
        $this->userLogin();
    }

    public function testCreateIntentions(){
        $college = factory(App\College::class)->create();
        $college->degrees()->sync(\App\Degree::estimatable()->get()->map(function($item){
            return $item->id;
        })->toArray());

        $params = [
            'college_id' => $college->id,
            'degree_id' => $college->degrees()->where('name', '硕士')->get()->first()->id,
            'speciality_name' => '这就是我的专业名称',
            'student_name' => '蜡笔小新',
            'requirement_contrast' => json_decode('[{"name":"雅思","require":"8","user_score":"6.5"},{"name":"听","require":"7","user_score":"1"},{"name":"说","require":"6","user_score":"2"},{"name":"读","require":"5","user_score":"3"},{"name":"写","require":"4","user_score":"4"},{"name":"大学平均成绩（211）","require":"85","user_score":"78"},{"name":"相关专业工作年限","require":"3","user_score":"4"}]', true)
        ];

        //测试添加意向
        $this->call('POST', route('intentions.store'), $params);
        $this->assertResponseOk();
        $this->seeInDatabase('intentions', ['student_name' => $params['student_name']]);
    }
}
