<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Mail;
use App\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;

use App\Services\VerifyCodeService;

class HomeController extends Controller
{
    protected $verify_code_service;

    protected $user;

    public function __construct(VerifyCodeService $verify_code_service)
    {
        $this->verify_code_service = $verify_code_service;
        $this->middleware('auth');
        $this->user = Auth::user();
    }

    public function index()
    {
        $user = $this->user;

        return view('home.index', compact('user'));
    }

    //保存个人资料
    public function saveProfile(Request $request){
        $username = $request->input('username', null);
        $email = $request->input('email', null);
        $avatar = $request->file('avatar', null);

        $update = false;

        $validation = [];

        if($username && $username != $this->user->username){
            $update = true;
            $this->user->username = $username;
            $validation['username'] = 'required|unique:users|max:255';
        }

        if($email && $email != $this->user->email){
            $update = true;
            $this->user->email = $email;
            $validation['email'] = 'required|unique:users';
        }

        if($avatar){
            $result = app('qiniu_uploader')->upload_file($avatar);
            $path = $result['path'];
            $update = true;
            $this->user->avatar_path = $path;
        }

        if($update){
            $this->validate($request, $validation);

            try{
                $this->user->save();
                Flash::message('保存个人资料成功');
            }catch(\Exception $e){
                Flash::message('保存个人资料失败');
            }
        }

        return back();

    }

    public function likeColleges(){
        $college_ids = $this->user->likedCollegeIds();
        $colleges = College::whereIn('id', $college_ids)->get();

        return view('home.like_colleges', compact('colleges'));
    }

    public function messages(){
        $message_client = new Mail();
        $messages = $message_client->getUnreadMessage(Auth::user()->id);

        return view('home.messages', compact('messages'));
    }

    public function readMessage($message_id){
        $message_client = new Mail();
        $message_client->readMessage(Auth::user()->id, $message_id);
        return redirect()->route('home.messages');
    }

    public function changePassword(Request $request){
        $user = Auth::user();
        $password = $request->input('password');
        $password_confirmation = $request->input('password_confirmation');

        $check = app('auth')->attempt([
            'id' => $user->id,
            'password' => $request->input('old_password')
        ]);

        if($check)
        {
            if($password != $password_confirmation){
                Flash::message('两次密码输入不一致');
                return back();
            }
            
            $user->password = bcrypt($password);
            $user->save();
            Flash::message('密码修改成功');
        } else{
            Flash::message('原密码输入错误');
        }

        return back();
    }

    public function changePhone(Request $request){
        $code = $request->get('code');
        $phone_number = $request->get('phone_number');

        if(!$this->validateVerifyCode($phone_number, $code)){
            return $this->errorResponse('验证码验证失败');
        }

        $user = Auth::user();
        $phone_number = $request->input('phone_number');
        $user->phone_number = $phone_number;
        $user->save();
        Flash::message('手机修改成功');
        return back();
    }

    private function validateVerifyCode($phone_number, $code){
        return $this->verify_code_service->testingVerifyCodeWithPhoneNumber($phone_number, $code);
    }
}
