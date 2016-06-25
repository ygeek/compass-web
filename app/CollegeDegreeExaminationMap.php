<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//学校学历对应考试映射表
class CollegeDegreeExaminationMap extends Model
{
    public static function getExaminationsWith($college, $education){
        $config = config("college_degree_examination_map.{$college}.{$education}");
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
}
