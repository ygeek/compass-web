<?php

namespace App\Http\Controllers;

use App\Intention;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Setting;
use App\College;
use App\Estimate;
use App\Degree;
use Uuid;
use Illuminate\Support\Facades\Auth;

class IntentionsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    //用户提交审核 创建Intention对象
    public function create(Request $request){
        $user = Auth::user();
        $estimate_id = $request->input('estimate_id');
        $selected_speciality_ids = $request->input('selected_speciality_ids');
        $estimate_data = Setting::get('estimate-'.$estimate_id);

        $intentions = $user->intentions;
        $intention_colleges = $intentions['intentions'];
        $new_intention_colleges = collect($intention_colleges)->map(function($college) use ($selected_speciality_ids){
            $res = [
                'college_id' => $college['college_id']
            ];

            $specialities = collect($college['specialities'])->filter(function($speciality) use ($selected_speciality_ids){
                return in_array($speciality['_id'], $selected_speciality_ids);  
            })->toArray();

            $res['specialities'] = $specialities;
            return $res;
        })->filter(function($college){
            return count($college['specialities']) > 0;
        });
        $intentions['intentions'] = $new_intention_colleges;

        $intentions['country_id'] = $estimate_data['selected_country'];

        $intention = new Intention();
        $intention->name = $estimate_data['name'];
        $intention->email = $user->email;
        $intention->phone_number = $user->phone_number;
        $intention->data = $intentions;
        $intention->save();

        return $this->okResponse();
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
            if($user->intentions['estimate_id'] != $estimate_id){
                $user->intentions = null;
                $user->save();
            }
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
            'require' => $speciality_intention_require,
            '_id' => Uuid::generate(4)->string
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
            $keys = array_keys($intentions['intentions'][$college_intention_index]['specialities']);
            foreach ($keys as $key) {
                if($intentions['intentions'][$college_intention_index]['specialities'][$key]['speciality_name'] == $speciality_name){
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

    //从意向单中删除专业
    public function destroy($id){
        $user = Auth::user();
        $intentions = $user->intentions;
        $intention_colleges = $intentions['intentions'];
        $new_intention_colleges = collect($intention_colleges)->map(function($college) use ($id){
            $res = [
                'college_id' => $college['college_id']
            ];

            $specialities = collect($college['specialities'])->filter(function($speciality) use ($id){
                return $speciality['_id'] != $id;
            })->toArray();

            $res['specialities'] = $specialities;
            return $res;
        })->filter(function($college){
            return count($college['specialities']) > 0;
        });
        $intentions['intentions'] = $new_intention_colleges;

        $user->intentions = $intentions;
        $user->save();

        return $this->okResponse();
    }
}
