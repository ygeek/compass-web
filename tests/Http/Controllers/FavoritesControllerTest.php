<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class FavoritesControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected $college;

    public function setUp()
    {
        parent::setUp();

        $this->seed();
        $this->userLogin();
        $this->college = factory(\App\College::class)->create();
    }

    public function testLikeACollege(){
        $user = User::first();

        $this->assertFalse($user->isLikeCollege($this->college->id));

        $this->call('POST', '/like_college', ['college_id' => $this->college->id]);
        $this->assertResponseOk();
        $this->assertEquals(1, count(\App\Setting::get('user:1:favorites')));

        $this->assertTrue($user->isLikeCollege($this->college->id));
    }

    public function testUnlikeACollege(){
        $user = User::first();

        $this->assertFalse($user->isLikeCollege($this->college->id));

        $this->call('POST', '/like_college', ['college_id' => $this->college->id]);
        $this->assertResponseOk();
        $this->assertEquals(1, count(\App\Setting::get('user:1:favorites')));

        $this->assertTrue($user->isLikeCollege($this->college->id));

        $this->call('POST', '/dislike_college', ['college_id' => $this->college->id]);
        $this->assertResponseOk();
        $this->assertEquals(0, count(\App\Setting::get('user:1:favorites')));
    }
}
