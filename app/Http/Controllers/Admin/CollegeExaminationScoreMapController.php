<?php

namespace App\Http\Controllers\Admin;

use App\College;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CollegeExaminationScoreMapController extends Controller
{
    public function create($college_id){
        $college = College::find($college_id);
        $examination_template = $college->examinationScoreMapTemplate();

        $degrees = $college->degrees()->where('estimatable', true)->get();
        return view('admin.college_examination_score_map.create', compact('examination_template', 'degrees'));
    }
}
