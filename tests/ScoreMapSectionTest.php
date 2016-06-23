<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ScoreMapSectionTest extends TestCase
{
    //测试分数段匹配结果
    //匹配成功返回True
    //否则返回False

    public function testMatch(){
        $section = new \App\ScoreMapSection('江西:>=500');
        $this->assertTrue($section->matching('江西:500'));
        $this->assertTrue($section->matching('江西:600'));
        $this->assertFalse($section->matching('北京:500'));
        $this->assertFalse($section->matching('江西:499'));
        $this->assertFalse($section->matching('499'));
        $this->assertFalse($section->matching('550'));

        $section = new \App\ScoreMapSection('500');
        $this->assertTrue($section->matching('500'));
        $this->assertFalse($section->matching('江西:500'));
        $this->assertFalse($section->matching('600'));

        $section = new \App\ScoreMapSection('100-200');
        $this->assertTrue($section->matching('100'));
        $this->assertTrue($section->matching('200'));
        $this->assertTrue($section->matching('150'));
        $this->assertFalse($section->matching('350'));

    }
}
