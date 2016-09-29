<?php

namespace App\Http\Controllers\Admin;

use App\AdministrativeArea;
use App\College;
use App\Degree;
use App\ExaminationScoreWeight;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

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
        $this->validate($request, [
            'name' => 'required'
        ]);

        $weight = new ExaminationScoreWeight($request->all());
        $weights = json_decode($request->input('weights'));
        $weight->weights = $weights;
        $weight->save();
        
        return redirect()->route('admin.examination_score_weights.index');
    }

    public function edit($weight_id){
        $weight = ExaminationScoreWeight::find($weight_id);
        return view('admin.examination_score_weights.edit', compact('weight'));
    }

    public function update($weight_id, Request $request){
        $weight = ExaminationScoreWeight::find($weight_id);
        $weights = json_decode($request->input('weights'));

        $weight->name = $request->input('name');
        $weight->weights = $weights;
        $weight->save();

        return redirect()->route('admin.examination_score_weights.index');
    }

    public function destroy($id)
    {
        $weight = ExaminationScoreWeight::find($id);
        if($weight->colleges->count() == 0){
            $weight->delete();
            Flash::message('删除成功');
        }else{
            Flash::message('有院校与其关联 无法删除');
        }
        return redirect()->route('admin.examination_score_weights.index');
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
