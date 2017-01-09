<?php

namespace App\Http\Controllers\Auth;

use App\Events\Event;
use App\Events\VerifyCodeSet;
use App\Services\Registrar;
use App\Services\VerifyCodeService;
use Illuminate\Http\Request;
use App\User;
use Validator;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $verify_code_service;

    protected $registrar;

    public function __construct(VerifyCodeService $verify_code_service, Registrar $registrar)
    {
        $this->verify_code_service = $verify_code_service;
        $this->registrar = $registrar;
        $this->middleware($this->guestMiddleware(), ['except' => ['logoutUser', 'createVerifyCodes']]);
    }

    public function createVerifyCodes(Request $request){
        $phone_number = $request->input('phone_number');
        $phone_country = $request->input('phone_country', 'china');
        $code = $this->verify_code_service->setVerifyCodeForPhoneNumber($phone_number);

        if(env('APP_DEBUG')){
            return $this->responseJson('ok', ['code' => $code]);
        }else{
            event(new VerifyCodeSet($code, $phone_number, $phone_country));
            return $this->responseJson('ok');
        }
    }

    public function register(Request $request){
        $code = $request->get('code');
        $phone_number = $request->get('phone_number');

        if(!$this->validateVerifyCode($phone_number, $code)){
            return $this->errorResponse('验证码验证失败');
        }

        try {
            $data = $request->only(['phone_number', 'password']);
            $data['register_ip'] = $request->ip();
            $this->registrar->create($data);

            return $this->okResponse();
        }catch (\Exception $e){
            return $this->errorResponse('创建用户失败');
        }
    }

    public function login(Request $request){
        $phone_number = $request->get('phone_number');
        $password = $request->get('password');

        if(Auth::attempt(['phone_number' => $phone_number, 'password' => $password])){
            $user = Auth::User();
            $userInfo = $user->toArray();
            $userInfo['avatarPath'] = $user->getAvatarPath();

            return $this->okResponse($userInfo);
        }else{
            return $this->errorResponse('登录信息错误');
        }
    }

    public function logoutUser(){
      Auth::logout();
      return redirect()->route('index');
    }

    public function resetPassword(Request $request) {
      $method = $request->method();

      if($request->isMethod('post')) {
        $code = $request->get('code');
        $phone_number = $request->get('phone_number');
        $password = $request->get('password');

        if(!$this->validateVerifyCode($phone_number, $code)){
          return $this->errorResponse('验证码验证失败');
        }else {
          $user = User::where('phone_number', $phone_number)->first();
          if(!$user) {
            return $this->errorResponse('用户不存在');
          }else {
            $user->password = bcrypt($password);
            $user->save();
            return $this->okResponse();
          }
        }
      }else {
        return $this->view('auth.reset_password');
      }
    }

    private function validateVerifyCode($phone_number, $code){
        return $this->verify_code_service->testingVerifyCodeWithPhoneNumber($phone_number, $code);
    }

}
