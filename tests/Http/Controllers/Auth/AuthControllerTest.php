<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\User;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;
    //获取验证码测试
    public function testCreateUser(){
        $phone_number = '13876543210';
        $params = [
            'phone_number' => $phone_number
        ];

        $this->call('POST', '/auth/verify_codes', $params);
        $this->assertResponseOk();
    }

    //用户注册测试
    public function testRegister(){
        $phone_number = $this->faker->phoneNumber;

        //获取验证码
        $params = [
            'phone_number' => $phone_number
        ];

        $response = $this->call('POST', '/auth/verify_codes', $params);
        $code = $response->getData()->data->code;

        //注册
        $params = [
            'phone_number' => $phone_number,
            'password' => $this->faker->password,
            'code' => 'fake_code'
        ];

        $this->call('POST', '/auth/register', $params);

        //注册失败
        $this->assertResponseStatus(422);

        $params['code'] = $code;
        $response = $this->call('POST', '/auth/register', $params);
        $this->assertResponseOk();

        $this->seeInDatabase('users', ['phone_number' => $params['phone_number']]);

        //重复注册
        $this->call('POST', '/auth/register', $params);
        $this->assertResponseStatus(422);
    }

    //用户登录测试
    public function testUserLogin(){
        $phone_number = $this->faker->phoneNumber;
        $password = $this->faker->password;

        $params = [
            'phone_number' => $phone_number,
            'password' => $password
        ];

        $this->call('POST', '/auth/login', $params);
        $this->assertResponseStatus(422);

        //获取验证码
        $params = [
            'phone_number' => $phone_number
        ];

        $response = $this->call('POST', '/auth/verify_codes', $params);
        $code = $response->getData()->data->code;
        //注册
        $params = [
            'phone_number' => $phone_number,
            'password' => $this->faker->password,
            'code' => $code
        ];

        $this->call('POST', '/auth/register', $params);
        $this->assertResponseOk();

        $this->seeInDatabase('users', ['phone_number' => $params['phone_number']]);

        $this->call('POST', '/auth/login', $params);
        $this->assertResponseOk();

    }

//    public function testSmsSender(){
//        $phone_number = '18170820396';
//        $smsService = app('sms');
//        $smsService->sendVerifyCode('1234', $phone_number);
//    }
}
