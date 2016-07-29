<?php

namespace App\Http\Controllers\Admin;

use App\College;
use App\CollegeExaminationScoreMap;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CollegeExaminationScoreMapController extends BaseController
{
    public function index($college_id){
        $college = College::find($college_id);
        if($college->examinationScoreMap){
            //已存在 修改
            $map = $college->examinationScoreMap;
            return view('admin.college_examination_score_map.create', compact('map', 'college'));
        }else{
            //不存在 新增
            $examination_template = $college->examinationScoreMapTemplate();
            $degrees = $college->degrees()->where('estimatable', true)->get();
            return view('admin.college_examination_score_map.create', compact('examination_template', 'degrees', 'college'));
        }

    }

    public function store($college_id, Request $request){
        $college = College::find($college_id);
        if($college->examinationScoreMap){
            //已存在 修改
            $map = $college->examinationScoreMap;
            $map->map = $request->input('map');
            $map->save();
        }else{
            //不存在 新增
            $map = new CollegeExaminationScoreMap();
            $map->college_id = $college_id;
            $map->map = $request->input('map');
            $map->save();
        }

        return $this->okResponse();
    }
}
