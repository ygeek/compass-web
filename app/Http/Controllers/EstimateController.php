<?php

namespace App\Http\Controllers;

use App\AdministrativeArea;
use App\CoreRangeSetting;
use App\CountryDegreeExaminationMap;
use App\Degree;
use App\College;
use App\Estimate;
use App\Examination;
use App\Setting;
use App\SpecialityCategory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Uuid;

use App\Http\Requests;

class EstimateController extends Controller
{
    public function stepFirst(Request $request){
        $selected_country_id = $request->input('selected_country_id', null);
        $selected_degree_id = $request->input('selected_degree_id', null);
        $selected_year = $request->input('selected_year', null);
        $countries = AdministrativeArea::countries()->get();
        $degrees = Degree::estimatable()->get();

        $now_year = date("Y");
        $years = [
            $now_year, $now_year + 1, $now_year + 2, '三年以后'
        ];

        $speciality_categories = SpecialityCategory::with('specialities')->get();
        return view('estimate.step_first', compact('countries', 'degrees', 'years', 'speciality_categories', 'selected_country_id', 'selected_degree_id', 'selected_year'));
    }

    public function stepSecond(Request $request){
        $selected_country = AdministrativeArea::find($request->input('selected_country_id'));
        $selected_degree = Degree::find($request->input('selected_degree_id'));
        $selected_speciality_name = $request->input('speciality_name');
        $estimate_checked = false;
        $user = Auth::user();
        if ($user!=null && $user->estimate!=null){
            $estimate_checked = true;
        }
        return view('estimate.step_second', compact('selected_degree', 'selected_country', 'selected_speciality_name', 'estimate_checked'));
    }

    /*
     * 生成评估结果
     */
    public function store(Request $request){
        $estimate_id = $request->input('estimate_id');
        if ($estimate_id!=null){
            $data = Setting::get($estimate_id);
        }
        else{
            $data = $request->input('data');
        }
        if(is_string($data)){
            $data = json_decode($data, true);
        }
        $selected_country = AdministrativeArea::find($data['selected_country']);
        $selected_degree = Degree::find($data['selected_degree']);
        $selected_speciality_name = $data['selected_speciality_name'];

        $examinations = $data['examinations'];//需要将前端提交的数据修改为ArrayOfObject的形式 Object包含两个值 examination_id和score

        //需要计算院校性质
        if($selected_degree->name == '硕士'){
            $recently_college_name = $data['recently_college_name'];
            $recently_college_type = Estimate::getRecentlyCollegeType($recently_college_name);

            //补充院校性质考试类型到examinations里面
            $examinations['院校性质'] = [
                'score' => $recently_college_type
            ];

            //修改大学平均成绩 增加ta
            $examinations['大学平均成绩']['tag'] = $recently_college_type;
            $examinations['大学平均成绩']['score_without_tag'] = $examinations['大学平均成绩']['score'];
            $data['examinations'] = $examinations;
        }

        $data['examinations'] = collect($data['examinations'])->filter(function($item){
            return !!$item['score'];
        });

        $student_scores = [];
        foreach ($examinations as $examination_name => $value) {
            //前端没有提交分数 Continue
            if(!$value['score'] || $value['score'] == ''){
                continue;
            }

            $examination = Examination::where('name', $examination_name)->first();
            $item = [
                'examination_id' => $examination->id
            ];

            if($examination->multiple_degree){
                $item[$value['degree'].':score'] = $value['score'];
            }else{
                $item['score'] = $value['score'];
            }

            $student_scores[] = $item;
        }

        $colleges = $this->estimateColleges($selected_degree, $selected_speciality_name);

        $res = [];
        foreach ($colleges as $college){
            try{
                $res[] = [
                    'college_id' => $college->id,
                    'score' => $college->calculateWeightScore($student_scores, $selected_degree)
                ];
            }catch (\Exception $e){
                continue;
            }
        }
        //Reduce结果
        $core_range_setting = (new CoreRangeSetting())->getCountryDegreeSetting($selected_country->id, $selected_degree->id);
        $reduce_result = Estimate::reduceScoreResult($res, $core_range_setting);
        //生成院校信息
        $reduce_colleges = Estimate::mapCollegeInfo($reduce_result, $selected_speciality_name, $selected_degree, $data);

        if ($estimate_id == null){
            $estimate_id = Uuid::generate(4);
            Setting::set('estimate-'.$estimate_id, $data);

            $user = Auth::user();
            if ($user!=null){
                $user->estimate = 'estimate-'.$estimate_id;
                $user->save();
            }
        }
        else{
            $estimate_id = str_replace('estimate-', '', $estimate_id);
        }

        return view('estimate.index', compact('reduce_colleges', 'examinations', 'selected_degree', 'selected_speciality_name', 'estimate_id', 'data'));
    }


    //获取需要遍历的院校列表
    //条件为有对应学历的专业
    private function estimateColleges($degree, $speciality_name){
        return College::whereHas('specialities', function($query) use ($degree, $speciality_name){
            $query->where('specialities.degree_id', $degree->id)
                  ->where('specialities.name', $speciality_name);
        })->get();
    }
}
