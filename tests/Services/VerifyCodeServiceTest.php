<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VerifyCodeServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSetVerifyCode()
    {
        $service = app('verify_code_service');
        $phone_number = '13838383333';

        $code = $service->setVerifyCodeForPhoneNumber($phone_number);
        $this->assertTrue($service->testingVerifyCodeWithPhoneNumber($code, $phone_number));
        $this->assertFalse($service->testingVerifyCodeWithPhoneNumber('fakecode', $phone_number));
    }
}
