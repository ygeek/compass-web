<?php

namespace App\Http\Controllers;

use App\Setting;
use App\College;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use Illuminate\Support\Facades\App;

class FavoritesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    //收藏一个学校
    public function store(Request $request){
        $college_id = $request->input('college_id');
        $college = College::find($college_id);

        $user_id = Auth::user()->id;
        $key = \App\User::likeKey($user_id);

        $set = $college->id;

        $exists_favorites = Setting::get($key);

        //原先不存在
        if(is_null($exists_favorites)){
            $set = [$set];
        }else{
            $exists_favorites[] = $set;
            $set = $exists_favorites;
        }

        Setting::set($key, $set);
        
        $college->like_nums += 1;
        $college->save();

        return $this->okResponse();
    }

    //取消收藏一个学校
    public function cancelFavorite(Request $request){
        $college_id = $request->input('college_id');
        $college = College::find($college_id);

        $user_id = Auth::user()->id;
        $key = \App\User::likeKey($user_id);

        $exists_favorites = Setting::get($key);
        if(is_null($exists_favorites)){
            return $this->okResponse();
        }else{
            $set = collect($exists_favorites)->filter(function($item) use ($college){
               return $item != $college->id;
            })->toArray();
            Setting::set($key, $set);
            $college->like_nums -= 1;
            $college->save();

            return $this->okResponse();
        }
    }
}
