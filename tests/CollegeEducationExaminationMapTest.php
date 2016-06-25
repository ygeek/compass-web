<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CollegeEducationExaminationMapTest extends TestCase
{

    //测试输入国家和学历 输出对应的考试
    public function testGivenCollegeAndEducationReturnExamination(){
        $college = '澳洲';
        $education = '本科';
        $examinations = \App\CollegeEducationExaminationMap::getExaminationsWith($college, $education);
        $this->assertTrue(!!$examinations);
    }
}
