<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CollegesControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        $this->adminLogin();
    }

    public function testGetCreatePage(){
        $this->call('GET', route('admin.colleges.create'));
        $this->assertResponseOk();
    }

    public function testGetCollegeList(){
        $this->call('GET', '/admin/colleges');
        $this->assertResponseOk();
        $this->assertViewHas('colleges');
    }

    public function testEditCollege(){
        $college = factory(App\College::class)->create();
        $this->call('GET', route('admin.colleges.edit', $college->id));
        $this->assertResponseOk();
        $this->assertViewHas('college');
    }

    public function testShowCollege(){
        $college = factory(App\College::class)->create();
        $this->call('GET', route('admin.colleges.show', $college->id));
        $this->assertResponseOk();
        $this->assertViewHas('college');
    }

    //测试创建学校
    public function testStoreCollege(){
        $this->seed('AdministrativeAreaSeeder');
        $this->seed('DegreeSeeder');

        $degree_ids = \App\Degree::all()->map(function($item){
           return $item->id;
        });

        $params = [
            'chinese_name' => '悉尼大学',
            'english_name' => 'University of Sydney',
            'description' => '悉尼大学的历史可以追溯到1848年，当时的新南威尔士名流威廉·温特沃斯（William Wentworth）
        在立法会议上提议将1830年建立的悉尼学院（Sydney College）扩大成一所大学。',
            'telephone_number' => '+61 2 9351 2222',
            'founded_in' => '1850',
            'address' => '澳大利亚悉尼',
            'website' => 'http://sydney.edu.au/',
            'qs_ranking' => 1,
            'us_new_ranking' => 2,
            'times_ranking' => 3,
            'domestic_ranking' => 1,
            'badge_path' => 'http://sydney.edu.au/etc/designs/corporate-commons/bower_components/corporate-frontend/dist/assets/img/USydLogo.svg',
            'background_image_path' => null,
            'administrative_area_id' => App\AdministrativeArea::whereIsRoot()->get()->first()->id,
            'degree_ids' => $degree_ids->toArray()
        ];

        $this->call('POST', '/admin/colleges', $params);
        $this->seeInDatabase('colleges', ['chinese_name' => '悉尼大学']);
        //同时存储了学历关联关系
        $this->seeInDatabase('college_degree', ['degree_id' => $degree_ids->first()]);
        $this->assertRedirectedToRoute('admin.colleges.index');
    }

    //测试修改学校
    public function testUpdateCollege(){
        $college = factory(App\College::class)->create();
        $params = [
            'chinese_name' => '蓝翔高级技工学校'
        ];

        $this->call('PATCH', route('admin.colleges.update', $college->id), $params);
        $this->assertRedirectedToRoute('admin.colleges.edit', $college->id);

        $college = \App\College::find($college->id);
        $this->assertEquals($params['chinese_name'], $college->chinese_name);
    }

    //测试删除学校
    public function testDeleteCollege(){
        $college = factory(App\College::class)->create();
        $this->seeInDatabase('colleges', ['id' => $college->id]);
        $this->call('DELETE', route('admin.colleges.destroy', $college->id));
        $this->assertRedirectedToRoute('admin.colleges.index');
        $this->notSeeInDatabase('colleges', ['id' => $college->id]);
    }
}
