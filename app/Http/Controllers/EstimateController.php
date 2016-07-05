<?php

namespace App\Http\Controllers;

use App\AdministrativeArea;
use App\CountryDegreeExaminationMap;
use App\Degree;
use App\College;
use App\Examination;
use App\SpecialityCategory;
use Illuminate\Http\Request;

use App\Http\Requests;

class EstimateController extends Controller
{
    public function stepFirst(){
        $countries = AdministrativeArea::countries()->get();
        $degrees = Degree::estimatable()->get();

        $now_year = date("Y");
        $years = [
            $now_year + 1, $now_year + 2
        ];

        $speciality_categories = SpecialityCategory::with('specialities')->get();
        return view('estimate.step_first', compact('countries', 'degrees', 'years', 'speciality_categories'));
    }

    public function stepSecond(Request $request){
        $selected_country = AdministrativeArea::find($request->input('selected_country_id'));
        $selected_degree = Degree::find($request->input('selected_degree_id'));
        return view('estimate.step_second', compact('selected_degree', 'selected_country'));
    }

    /*
     * 生成评估结果
     */
    public function store(Request $request){
        $data = $request->input('data');
        $selected_country = AdministrativeArea::find($data['selected_country']);
        $selected_degree = Degree::find($data['selected_degree']);

        $examinations = $data['examinations'];//需要将前端提交的数据修改为ArrayOfObject的形式 Object包含两个值 examination_id和score

        $student_scores = [];
        foreach ($examinations as $examination_name => $value) {
            //前端没有提交分数 Continue
            if(!$value['score']){
                continue;
            }

            $examination = Examination::where('name', $examination_name)->first();
            $item = [
                'examination_id' => $examination->id,
            ];

            if($examination->multiple_degree){
                $item[$value['degree'].':score'] = $value['score'];
            }else{
                $item['score'] = $value['score'];
            }

            $student_scores[] = $item;
        }

        $colleges = $this->estimateColleges();

        $res = $colleges->map(function($college) use ($student_scores, $selected_degree){
            return [
                'college' => $college->id,
                'score' => $college->calculateWeightScore($student_scores, $selected_degree)
            ];
        });
    }

    private function estimateColleges($params=null){
        return College::all();
    }
}
