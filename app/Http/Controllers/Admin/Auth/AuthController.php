<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Auth;

class AuthController extends Controller
{
    public function getLogin(){

    }

    public function postLogin(Request $request){
        $username = $request->input('username');
        $password = $request->input('password');
        if(Auth::guard('admin')->attempt(compact('username', 'password'))){
            return redirect()->route('admin_home');
        }
        return redirect()->back()->withInput()->with('message', '登录失败');
    }
}
