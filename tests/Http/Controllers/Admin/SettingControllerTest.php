<?php

use App\Setting;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SettingControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testSetting(){
        $key = 'some_key';
        $this->assertTrue(is_null(Setting::get($key)));

        $value = 'some_value';

        $this->assertTrue(Setting::set($key, $value));
        $this->assertEquals($value, Setting::get($key));
    }

    //测试设置核心/冲刺概率
    public function testSetSprintRange(){
        $key = 'core_range';
        $this->call('GET', route('admin.setting.index', ['key' => $key]));
        $this->assertResponseOk();
    }
}
