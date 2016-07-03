<?php

namespace App\Http\Controllers\Admin;

use App\AdministrativeArea;
use App\College;
use App\Degree;
use App\ExaminationScoreWeight;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ExaminationScoreWeightsController extends BaseController
{
    public function index(){
        $weights = ExaminationScoreWeight::with(['country', 'degree'])->paginate();
        return view('admin.examination_score_weights.index', compact('weights'));
    }

    public function create(){
        $countries = AdministrativeArea::countries()->get();
        $degrees = Degree::estimatable()->get();
        return view('admin.examination_score_weights.create', compact('countries', 'degrees'));
    }
    
    public function store(Request $request){
        ExaminationScoreWeight::create($request->all());
    }

    public function colleges($weight_id){
        $weight = ExaminationScoreWeight::find($weight_id);
        $colleges = College::join('college_degree', 'college_degree.college_id', '=', 'colleges.id')
            ->orWhere(function ($query) use ($weight_id){
                $query->where('college_degree.examination_score_weight_id', '=', $weight_id)
                    ->orWhereNull('college_degree.examination_score_weight_id');
            })
            ->where('college_degree.degree_id', '=', $weight->degree_id)
            ->where('colleges.country_id', '=', $weight->country_id)
            ->get();
        return $this->okResponse($colleges);
    }

    public function updateColleges($weight_id, Request $request){
        $weight = ExaminationScoreWeight::with('degree')->find($weight_id);
        $degree = $weight->degree;
        $colleges_data = $request->input();
        foreach ($colleges_data as $college_data){
            $query =  DB::table('college_degree')->where('college_id', $college_data['id'])
                        ->where('degree_id', $degree->id);

            if($college_data['checked']){
                $query->update(['examination_score_weight_id' => $weight->id]);
            }else{
                $query->update(['examination_score_weight_id' => null]);
            }
        }
        return $this->okResponse();
    }
}
