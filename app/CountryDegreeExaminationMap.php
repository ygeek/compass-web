<?php

namespace App;

//学校学历对应考试映射表
use Illuminate\Support\Debug\Dumper;

class CountryDegreeExaminationMap
{
    public static function getExaminationsWith($country, $degree){
        $allExaminations = self::getAllExaminationsWith($country, $degree, false);
        //排除掉is_requirement为True的项目
        return $allExaminations;
    }

    public static function getAllExaminationsWith($country, $degree, $is_requirement=true){
        if(is_numeric($country)){
            $country = AdministrativeArea::find($country)->name;
        }

        if(is_numeric($degree)){
            $degree = Degree::find($degree)->name;
        }
        $config = config("college_degree_examination_map.{$country}.{$degree}");
        $res = [];

        foreach ($config as $item){
            if(!is_array($item)){
                $item = [$item];
            }

            $examinations = Examination::whereIn('name', $item)->get();
            $values = [];

            foreach ($examinations as $examination){
                if(!$is_requirement && $examination->is_requirement){
                    continue 2;
                }
                $values[] = $examination->id;
            }

            $visible = implode('/', $item);

            $res[] = [
                'ids' => $values,
                'visible' => $visible
            ];
        }

        return collect($res);
    }

    public static function getExaminationsGroupByDegree($country, $degress){
        $examinations_by_degree = [];
        foreach ($degress as $degree){
            if(!$degree->estimatable){
                continue;
            }

            $degree_examinations = [];
            $examinations = self::getAllExaminationsWith($country->name, $degree->name);
            foreach ($examinations as $examination){
                $degree_examinations[] = $examination['ids'];
            }

            $list_of_degree_examinations = Examination::whereIn('id',
                collect($degree_examinations)->flatten()->unique()
            )->get();

            $degree_examinations = [];

            foreach ($list_of_degree_examinations as $examination){
                $res = [
                    'examination_id' => $examination->id,
                    'examination_name' => $examination->name,
                    'requirement' => null,
                    'tagable' => $examination->tagable,
                    'sections' => collect($examination->sections)->map(function($section){ return ['name' => $section, 'requirement' => null];})
                ];

                if($res['examination_name'] == '院校性质'){
                    continue;
                }

                //特殊处理
                //平均成绩这门考试 在本科 tagable为false
                if($res['examination_name'] == '平均成绩' && $degree->name == '本科'){
                    $res['tagable'] = false;
                }

                $degree_examinations[] = $res;

            }

            $examinations_by_degree[] = [
                'id' => $degree->id,
                'name' => $degree->name,
                'examinations' =>  $degree_examinations
            ];
        }
        return $examinations_by_degree;
    }
}
