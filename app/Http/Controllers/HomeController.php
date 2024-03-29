<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Mail;
use App\College;
use App\Setting;
use App\AdministrativeArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use Jenssegers\Agent\Agent;
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

        $agent = new Agent();
        if ($agent->isMobile()) {
            $user = $this->user;
            $college_ids = $this->user->likedCollegeIds();
            $colleges = College::whereIn('id', $college_ids)->get();

            $data = [
                'username' => $user['name'],
                'userAvatar' => $user['avatar_path'],
                'collections' => $colleges
            ];
            return $this->view('home.index', compact('data'));
        } else {
            $user = $this->user;

            return $this->view('home.index', compact('user'));
        }


    }

    //移动端
    public function indexMobile()
    {
        $user = $this->user;
        $message_client = new Mail();
        $messages = $message_client->getUnreadMessage(Auth::user()->id);
        $college_ids = $this->user->likedCollegeIds();
        $colleges = College::whereIn('id', $college_ids)->get();

        $intentions = $this->user->intentions;
        if(is_null($intentions)){
            $intentions = [];
        }else{
            $intentions['degree'] = \App\Degree::find($intentions['degree_id']);
            $intentions['intentions'] = collect($intentions['intentions'])->map(function($intention) use ($intentions){
                $college = College::with(['specialities' => function($q) use ($intentions){
                    $q->where('specialities.degree_id', $intentions['degree']->id);
                }])->where('id', $intention['college_id'])->get()->first();
                $intention['college'] = $college->toArray();
                $intention['college']['toefl_requirement'] = $college->toeflRequirement($intentions['degree']->name);
                $intention['college']['ielts_requirement'] = $college->ieltsRequirement($intentions['degree']->name);
                $intention['badge_path'] = app('qiniu_uploader')->pathOfKey($college->badge_path);
                $intention['redirect_url'] = route('colleges.show', ['key' => $college->key]);
                $intention['college']['liked'] = 0;
                if(app('auth')->user()){
                    if(app('auth')->user()->isLikeCollege($intention['college']['id']))
                        $intention['college']['liked'] = 1;
                }
                $area = AdministrativeArea::where('id',$intention['college']['administrative_area_id'])->get();
                $area_string = $area[0]->name;
                while ($area[0]->parent_id!=null){
                    $area = AdministrativeArea::where('id',$area[0]->parent_id)->get();
                    $area_string .= " , ".$area[0]->name;
                }
                $intention['college']['area'] = $area_string;
                return $intention;
            });
        }

        $data = [
            'username' => $user['name'],
            'userAvatar' => $user['avatar_path'],
            'messages' => $messages,
            'collections' => $colleges,
            'willings' => $intentions
        ];
        return view('#', compact('data'));
    }

    //保存个人资料
    public function saveProfile(Request $request)
    {
        $username = $request->input('username', null);
        $email = $request->input('email', null);
        $avatar = $request->file('avatar', null);

        $update = false;

        $validation = [];

        if ($username && $username != $this->user->username) {
            $update = true;
            $this->user->username = $username;
            $validation['username'] = 'required|unique:users|max:255';
        }

        if ($email && $email != $this->user->email) {
            $update = true;
            $this->user->email = $email;
            $validation['email'] = 'required|unique:users';
        }

        if ($avatar) {
            $result = app('qiniu_uploader')->upload_file($avatar);
            $path = $result['path'];
            $update = true;
            $this->user->avatar_path = $path;
        }

        if ($update) {
            $this->validate($request, $validation);

            try {
                $this->user->save();
                Flash::message('保存个人资料成功');
            } catch (\Exception $e) {
                Flash::message('保存个人资料失败');
            }
        }

        return back();

    }

    public function likeColleges()
    {
        $college_ids = $this->user->likedCollegeIds();
        $colleges = College::whereIn('id', $college_ids)->get();

        return $this->view('home.like_colleges', compact('colleges'));
    }

    public function messages()
    {
        $message_client = new Mail();
        $messages = $message_client->getUnreadMessage(Auth::user()->id);

        return $this->view('home.messages', compact('messages'));
    }

    public function readMessage($message_id)
    {
        $message_client = new Mail();
        $message_client->readMessage(Auth::user()->id, $message_id);
        return redirect()->route('home.messages');
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $password = $request->input('password');
        $password_confirmation = $request->input('password_confirmation');
        $is_api = $request->input('api', false);

        $check = app('auth')->attempt([
            'id' => $user->id,
            'password' => $request->input('old_password')
        ]);

        if ($check) {
            if ($password != $password_confirmation) {
                if($is_api) {
                  return $this->errorResponse('两次密码输入不一致');
                } else {
                  Flash::message('两次密码输入不一致');
                  return back();
                }
            }

            $user->password = bcrypt($password);
            $user->save();
            if($is_api) {
              return $this->okResponse();
            }else {
              Flash::message('密码修改成功');
            }
        } else {
            if($is_api) {
              return $this->errorResponse('原密码输入错误');
            } else {
              Flash::message('原密码输入错误');
            }
        }

        return back();
    }

    public function changePhone(Request $request)
    {
        $is_api = $request->input('api', false);
        $code = $request->get('code');
        $phone_number = $request->get('phone_number');

        if (!$this->validateVerifyCode($phone_number, $code)) {
            return $this->errorResponse('验证码验证失败');
        }

        $user = Auth::user();
        $phone_number = $request->input('phone_number');
        $user->phone_number = $phone_number;
        $user->save();
        if($is_api) {
          return $this->okResponse();
        }else {
          Flash::message('手机修改成功');
          return back();
        }
    }

    public function intentions()
    {
        $user = Auth::user();
        $intentions = $this->user->intentions;
        $intention_colleges = [];
        $commited_intention_ids = Setting::get('user-commited-intention-ids-'.$user->id, []);

        if (is_null($intentions)) {
            $intentions = collect([]);
        } else {
            $intentions = collect($intentions)->map(function ($intention) use ($intentions, &$intention_colleges) {
                $intention['degree'] = \App\Degree::find($intention['degree_id']);
                $college = College::withTrashed()->with('specialities')->where('id', $intention['college_id'])->get()->first();

                $intention_college = $college->toArray();
                $intention_college['badge_path'] = app('qiniu_uploader')->pathOfKey($college->badge_path);
                $intention_college['toefl_requirement'] = $college->toeflRequirement($intention['degree']->name);
                $intention_college['ielts_requirement'] = $college->ieltsRequirement($intention['degree']->name);
                $intention_college['parent_location'] = $college->administrativeArea->parent->name;
                $intention_college['location'] = $college->administrativeArea->name;
                $intention_college['liked'] = 0;
                if(app('auth')->user()){
                    if(app('auth')->user()->isLikeCollege($intention_college['id'])){
                      $intention_college['liked'] = 1;
                    }
                }

                if($college->administrativeArea->parent->parent) {
                  $intention_college['parent_location'] = $college->administrativeArea->parent->parent->name;
                }

                $intention_colleges[$college->id] = $intention_college;

                $intention['college'] = $intention_college;

                $intention['badge_path'] = app('qiniu_uploader')->pathOfKey($college->badge_path);
                $intention['redirect_url'] = route('colleges.show', ['key' => $college->key]);

                $intention['college']['liked'] = 0;
                if (app('auth')->user()) {
                    if (app('auth')->user()->isLikeCollege($intention['college']['id']))
                        $intention['college']['liked'] = 1;
                }

                $area = AdministrativeArea::where('id', $intention['college']['administrative_area_id'])->get();
                $area_string = $area[0]->name;
                while ($area[0]->parent_id != null) {
                    $area = AdministrativeArea::where('id', $area[0]->parent_id)->get();
                    $area_string .= " , " . $area[0]->name;
                }

                $intention['college']['area'] = $area_string;

                return $intention;
            })->groupBy('college_id');
        }

        $speciality_categories = \App\SpecialityCategory::all()->toArray();

        return $this->view('home.intentions', compact('intentions', 'intention_colleges', 'speciality_categories', 'commited_intention_ids'));
    }

    private function validateVerifyCode($phone_number, $code)
    {
        return $this->verify_code_service->testingVerifyCodeWithPhoneNumber($phone_number, $code);
    }
}
