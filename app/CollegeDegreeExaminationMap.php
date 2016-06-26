<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//学校学历对应考试映射表
class CollegeDegreeExaminationMap extends Model
{
    public static function getExaminationsWith($country, $degree){
        if(is_numeric($country)){
            $country = AdministrativeArea::find($country)->name;
        }

        if(is_numeric($degree)){
            $degree = Degree::find($degree)->name;
        }
        $config = config("college_degree_examination_map.{$country}.{$degree}");
        return collect($config)->map(function($item){
            if(!is_array($item)){
                $item = [$item];
            }

            $examinations = Examination::whereIn('name', $item)->get();
            $values = $examinations->map(function($item){
                return $item->id;
            })->toArray();

            $visible = implode('/', $item);

            return [
                'ids' => $values,
                'visible' => $visible
            ];
        });
    }

    public static function getAllExaminations(){
        $config = config('college_degree_examination_map');

    }
}
