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
        if(is_null($degrees)){
            $degree = $instance->degree;
            $degrees = [$degree];
        }

        if($requirement){
            //已存在 修改
            return view('admin.requirement.create', compact('requirement', 'type', 'id'));
        }else{
            //不存在 新建
            $template = CountryDegreeExaminationMap::getExaminationsGroupByDegree($country, $degrees);
            $template = collect($template)->map(function($degree){
                return [
                    'id' => $degree['id'],
                    'name' => $degree['name'],
                    'examinations' => collect($degree['examinations'])->map(function($item){
                        if($item['tagable']){
                            $requirement = null;

                            if($item['examination_name'] == '高考'){
                                $provinces = collect(config('provinces'));
                                $requirement = $provinces->map(function($province){
                                    return [
                                        'tag_name' => $province,
                                        'tag_value' => null
                                    ];
                                });
                            }

                            if($item['examination_name'] == '大学平均成绩'){
                                $requirement = collect(['985', '211', '双非'])->map(function($province){
                                    return [
                                        'tag_name' => $province,
                                        'tag_value' => null
                                    ];
                                });
                            }

                            $item['requirement'] = $requirement;
                        }
                        return $item;
                    })
                ];
            });
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
