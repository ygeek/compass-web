<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MessagesControllerTest extends TestCase
{
    public function testGetMessages(){
        $this->call('GET', '/admin/messages/create');
        $this->assertResponseOk();
    }

    public function testGetMessagesIndex(){
        $this->call('GET', '/admin/messages');
        $this->assertResponseOk();
    }
}
