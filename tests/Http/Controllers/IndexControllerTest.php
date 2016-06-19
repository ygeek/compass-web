<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexControllerTest extends TestCase
{

    public function testGetIndexPage()
    {
        $this->call('GET', '/');
        $this->assertResponseOk();
    }
}
