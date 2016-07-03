<?php

namespace App\Http\Controllers\Admin;

use App\CountryDegreeExaminationMap;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RequirementController extends BaseController
{
    public function index($type, $id){

        $instance = $type::find($id);
        $requirement = $instance->requirement;

        $country = $instance->country;
        $degrees = $instance->degrees;

        if($requirement){
            //已存在 修改
            return view('admin.requirement.create', compact('requirement', 'type', 'id'));
        }else{
            //不存在 新建
            $template = CountryDegreeExaminationMap::getExaminationsGroupByDegree($country, $degrees);
            return view('admin.requirement.create', compact('template', 'type', 'id'));
        }
    }

    public function store($type, $id, Request $request){
        $requirement = $request->input('requirement');
        $instance = $type::find($id);

        $instance->requirement = $requirement;
        $instance->save();
        return $this->okResponse();
    }
}
