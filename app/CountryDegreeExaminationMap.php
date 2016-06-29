<?php

namespace App;

//学校学历对应考试映射表
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
}
