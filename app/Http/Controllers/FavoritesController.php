<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use Illuminate\Support\Facades\App;

class FavoritesController extends Controller
{
    //收藏一个学校
    public function store(Request $request){
        $college_id = $request->input('college_id');

        $user_id = Auth::user()->id;
        $key = \App\User::likeKey($user_id);

        $set = null;

        $exists_favorites = Setting::get($key);

        //原先不存在
        if(is_null($exists_favorites)){
            $set = [$college_id];
        }else{
            $exists_favorites[] = $set;
            $set = $exists_favorites;
        }

        Setting::set($key, $set);
        return $this->okResponse();
    }

    //取消收藏一个学校
    public function cancelFavorite(Request $request){
        $college_id = $request->input('college_id');
        $user_id = Auth::user()->id;
        $key = \App\User::likeKey($user_id);

        $exists_favorites = Setting::get($key);
        if(is_null($exists_favorites)){
            return $this->okResponse();
        }else{
            $set = collect($exists_favorites)->filter(function($item) use ($college_id){
               return $item != $college_id;
            })->toArray();
            Setting::set($key, $set);
            return $this->okResponse();
        }
    }
}
