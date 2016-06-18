<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Admin;

class AdminAuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected $admin;

    public function setUp(){
        parent::setUp();
        $this->admin = Admin::create([
            'username' => 'admin',
            'password' => bcrypt('pass'),
            'name' => 'chareice'
        ]);
    }

    public function testAdminUserLogin(){
        //用户未登录
        $this->assertFalse(app('auth')->guard('admin')->check());
        //用户访问管理界面
        $this->call('GET', '/admin/login');
        $this->assertResponseOk();

        //登录用户
        $params = [
            'username' => 'admin',
            'password' => 'pass'
        ];

        $this->call('POST', '/admin/login', $params);
        //登陆成功
        $this->assertRedirectedToRoute('admin_home');
        $this->assertTrue(app('auth')->guard('admin')->check());
    }

    public function testAdminLoginFailed()
    {
        $params = [
            'username' => 'admin111',
            'password' => 'pass'
        ];

        $this->call('POST', '/admin/login', $params);
        $this->assertFalse(app('auth')->guard('admin')->check());
    }
}
