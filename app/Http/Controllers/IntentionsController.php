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
use Illuminate\Support\Facades\Log;


class IntentionsController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    //用户提交审核 创建Intention对象
    public function create(Request $request){
        $user = Auth::user();
        $selected_speciality_ids = $request->input('selected_speciality_ids');
        $commited_intention_ids = Setting::get('user-commited-intention-ids-'.$user->id, []);

        $user_last_estimate_id = $user->estimate;

        $estimate_data = Setting::get($user_last_estimate_id);

        if(!is_array($selected_speciality_ids))
        {
          $selected_speciality_ids = json_decode($selected_speciality_ids);
        }

        $user_intentions = $user->intentions;

        $intentions = collect($user_intentions)->filter(function($intention) use ($selected_speciality_ids){
          return in_array($intention['_id'], $selected_speciality_ids);
        });

        $intention = new Intention();
        $intention->name = $estimate_data['name'];
        $intention->email = $user->email;
        $intention->phone_number = $user->phone_number;
        $intention->data = $intentions->toArray();
        $intention->save();

        Setting::set('user-commited-intention-ids-'.$user->id, array_merge($commited_intention_ids, $selected_speciality_ids));
        return $this->okResponse();
    }

    public function store(Request $request){
        $user = Auth::user();
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
        $intentions = $user->intentions;

        //构造用户分数
        $user_scores = [];
        foreach ($requirement_contrast as $contrast) {
            $user_scores[$contrast['name']] = $contrast['user_score'];
        }

        //构建意向专业数据结构
        $speciality_intention_require = [];
        foreach ($requirement_contrast as $contrast) {
            $speciality_intention_require[$contrast['name']] = $contrast['require'];
        }

        $student_scores = Estimate::grabStudentScoreFromEstimateData($estimate_data);

        $intention = [
            "estimate_id" => $estimate_id,
            "user_scores" => $user_scores,
            'college_id' => $college_id,
            "degree_id" => $degree->id,
            'speciality_name' => $speciality_name,
            'require' => $speciality_intention_require,
            '_id' => Uuid::generate(4)->string,
            'score' => $college->calculateWeightScore($student_scores, $degree),
            'requirement_contrast' => $requirement_contrast,
        ];

        $intentions[] = $intention;

        $user->intentions = $intentions;
        $user->save();

        return $this->okResponse();
    }

    //从意向单中删除专业
    public function destroy($id){
        $user = Auth::user();
        $intentions = $user->intentions;
        $new_intentions = [];

        foreach($intentions as $intention) {
          if($intention['_id'] != $id) {
            $new_intentions[] = $intention;
          }
        }

        $user->intentions = $new_intentions;
        $user->save();

        return $this->okResponse();
    }
}
