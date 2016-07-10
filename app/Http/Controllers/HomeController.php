<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return view('home.index', compact('user'));
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
}
