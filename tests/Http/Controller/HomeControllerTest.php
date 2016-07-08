<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testUserCanAccessHomePage(){
        $this->call('GET', '/home');
        $this->assertRedirectedTo('/login');

        $this->userLogin();
        $this->call('GET', '/home');
        $this->assertResponseOk();
    }
}
