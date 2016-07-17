<?php

namespace App\Http\Controllers;

use App\Intention;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Setting;
use App\College;
use App\Estimate;
use App\Degree;

use Illuminate\Support\Facades\Auth;

class IntentionsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    //用户意向单
    public function index(){

    }

    public function store(Request $request){

        $estimate_id = $request->input('estimate_id');
        $estimate_data = Setting::get('estimate-'.$estimate_id);

        $college_id = $request->input('college_id');
        $speciality_name = $request->input('speciality_name');
        $degree_id = $request->input('degree_id');

        $college = College::find($college_id);
        $degree = Degree::find($degree_id);

        $speciality_require = $college->getSpecialityRequirement($speciality_name, $degree);

        //该专业和考生分数的对照数据
        $requirement_contrast = Estimate::grabContrastFromRequirement($speciality_require['requirement'], $estimate_data);


        $user = Auth::user();

        if($user->intentions){
            //重新评估
            $user->intentions['estimate_id'] != $estimate_id;
            $user->intentions = null;
            $user->save();
        }

        if(!$user->intentions){
            //还没有意向
            $user_scores = [];
            foreach ($requirement_contrast as $contrast) {
                $user_scores[$contrast['name']] = $contrast['user_score'];
            }

            $intentions = [
                "estimate_id" => $estimate_id,
                "user_scores" => $user_scores,
                "degree_id" => $degree->id,
                "intentions" => []
            ];
        }else{
            $intentions = $user->intentions;
        }


        //构建意向专业数据结构
        $speciality_intention_require = [];
        foreach ($requirement_contrast as $contrast) {
            $speciality_intention_require[$contrast['name']] = $contrast['require'];
        }

        $speciality_intention = [
            'speciality_name' => $speciality_name,
            'require' => $speciality_intention_require
        ];

        //判断该院校有没有存在意向中
        $college_intention_index = null;

        for ($i=0; $i < count($intentions['intentions']); $i++) { 
           if($intentions['intentions'][$i]['college_id'] == $college_id){
                $college_intention_index = $i;
            }
        }

        if(!is_null($college_intention_index)){
            //判断专业是否存在意向中
            $speciality_index = null;
            for ($i=0; $i < count($intentions['intentions'][$college_intention_index]['specialities']); $i++) { 
                if($intentions['intentions'][$college_intention_index]['specialities'][$i]['speciality_name'] == $speciality_name){
                    $speciality_index = $i;
                }
            }

            if(is_null($speciality_index)){
                //将专业添加到意向单
                $intentions['intentions'][$college_intention_index]['specialities'][] = $speciality_intention;
            }else{
                //专业已经添加到了意向单
            }
        }else{
            $intentions['intentions'][] = [
                'college_id' => $college_id,
                'specialities' => [
                    $speciality_intention
                ]
            ];
        }


        $user->intentions = $intentions;
        $user->save();

        return $this->okResponse();
    }
}
