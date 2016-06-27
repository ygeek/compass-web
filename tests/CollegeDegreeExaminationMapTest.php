<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CollegeDegreeExaminationMapTest extends TestCase
{
    use DatabaseMigrations;

    //转化配置文件为程序可读的数据结构(包含ID)
    public function testTransferConfigToLogicDataStruct()
    {
        $this->seed('ExaminationSeeder');
        $contry = '新西兰';
        $degree = '本科';

        $map = \App\CountryDegreeExaminationMap::getExaminationsWith($contry, $degree);
        //var_dump($map);
    }
}
