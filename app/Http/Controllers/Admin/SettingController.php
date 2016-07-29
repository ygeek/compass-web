<?php

namespace App\Http\Controllers\Admin;

use App\Setting;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Input;
class SettingController extends BaseController
{
    public function index($key){
        $value = Setting::get($key);
        if($value){
            //已存在 修改
            return view('admin.setting.create_'. $key, compact('value', 'key'));
        }else{
            //不存在 新建
            return view('admin.setting.create_'. $key, compact('key'));
        }
    }

    public function store($key, Request $request){
        $value = $request->input('value');
        Setting::set($key, $value);
        return $this->okResponse();
    }
}
