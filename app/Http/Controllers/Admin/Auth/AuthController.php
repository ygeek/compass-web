<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Auth;

class AuthController extends Controller
{

    protected $redirectTo = '/admin';

    public function __construct()
    {
        $this->middleware('guest:admin');
    }

    public function getLogin(){
        return view('admin.auth.login');
    }

    public function postLogin(Request $request){
        $username = $request->input('username');
        $password = $request->input('password');
        if(Auth::guard('admin')->attempt(compact('username', 'password'))){
            return redirect()->route('admin_home');
        }
        return redirect()->back()->withInput()->withErrors('登录失败');
    }
}
