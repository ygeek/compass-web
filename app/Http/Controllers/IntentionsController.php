<?php

namespace App\Http\Controllers;

use App\Intention;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class IntentionsController extends Controller
{
    public function store(Request $request){
        $intention = new Intention($request->all());

        $user = Auth::user();

        $intention->user_id = $user->id;
        $intention->student_phone_number = $user->phone_number;
        $intention->student_email = $user->email;

        if($intention->save()){
            $this->okResponse();
        }else{
            $this->errorResponse('添加意向单失败');
        }

    }
}
