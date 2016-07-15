<?php

namespace App\Http\Controllers;

use App\AdministrativeArea;
use App\CoreRangeSetting;
use App\CountryDegreeExaminationMap;
use App\Degree;
use App\College;
use App\Estimate;
use App\Examination;
use App\SpecialityCategory;
use Illuminate\Http\Request;

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
            $now_year + 1, $now_year + 2
        ];

        $speciality_categories = SpecialityCategory::with('specialities')->get();
        return view('estimate.step_first', compact('countries', 'degrees', 'years', 'speciality_categories', 'selected_country_id', 'selected_degree_id', 'selected_year'));
    }

    public function stepSecond(Request $request){
        $selected_country = AdministrativeArea::find($request->input('selected_country_id'));
        $selected_degree = Degree::find($request->input('selected_degree_id'));
        $selected_speciality_name = $request->input('speciality_name');
        return view('estimate.step_second', compact('selected_degree', 'selected_country', 'selected_speciality_name'));
    }

    /*
     * 生成评估结果
     */
    public function store(Request $request){
        $data = $request->input('data');
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
        $colleges = $this->estimateColleges();

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

        return view('estimate.index', compact('reduce_colleges', 'examinations', 'selected_speciality_name'));
    }


    private function estimateColleges($params=null){
        return College::all();
    }
}
