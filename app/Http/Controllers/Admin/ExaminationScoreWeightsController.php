<?php

namespace App\Http\Controllers\Admin;

use App\AdministrativeArea;
use App\Degree;
use App\ExaminationScoreWeight;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ExaminationScoreWeightsController extends BaseController
{
    public function index(){

    }

    public function create(){
        $countries = AdministrativeArea::countries()->get();
        $degrees = Degree::all();
        return view('admin.examination_score_weights.create', compact('countries', 'degrees'));
    }
    
    public function store(Request $request){
        ExaminationScoreWeight::create($request->all());
    }
}
