<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexControllerTest extends TestCase
{
  use DatabaseMigrations;

  //用户未登录不能访问
  public function testGetAdminIndexPage(){
    $this->call('GET', '/admin/');
    $this->assertResponseStatus(302);
  }

  //管理员登录后访问
  public function  testAdminLoginThenAccessIndexPage(){
    $this->adminLogin();
    $this->call('GET', '/admin/');
    $this->assertResponseOk();
  }
}
