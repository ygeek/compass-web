<?php
namespace App;

class Estimate{
    //将遍历院校生成的分数结果 按照冲刺概率归类
    public static function reduceScoreResult($result, $core_range_setting){
        $core_count = $core_range_setting['core']['count'];
        $core_range = $core_range_setting['core']['range'];

        $sprint_count = $core_range_setting['sprint']['count'];
        $sprint_range = $core_range_setting['sprint']['range'];

        $college_results = collect($result);

        $core_colleges = self::grabMathingColleges($college_results, $core_range, $core_count);
        $sprint_colleges = self::grabMathingColleges($college_results, $sprint_range, $sprint_count);

        return [
            'sprint' => $sprint_colleges,
            'core' => $core_colleges
        ];
    }

    public static function grabMathingColleges($college_results, $range, $count){
        return $college_results->filter(function($college_result) use ($range){
            $score = $college_result['score'];
            $mather = new ScoreMapSection($range);
            //分数在该区间内 是匹配院校
            return $mather->matching($score);
        })->sortByDesc('score')->take($count);
    }

    //$selected_speciality_name用户选择的专业
    public static function mapCollegeInfo($reduce_result, $selected_speciality_name, $selected_degree, $examinations){
        //预先从数据库加载院校
        $sprint_colleges_ids = collect($reduce_result['sprint'])->map(function ($sprint_college){
            return $sprint_college['college_id'];
        });

        $core_college_ids = collect($reduce_result['core'])->map(function ($core_college){
            return $core_college['college_id'];
        });

        $college_ids = $sprint_colleges_ids->merge($core_college_ids);
        $colleges = College::whereIn('id', $college_ids)->get();

        $result = [];

        foreach ($reduce_result as $reduce_college_key => $reduce_colleges){
            $result[$reduce_college_key] = [];
            foreach ($reduce_colleges as $reduce_college){
                $res = $reduce_college;
                $college = $colleges->where('id', $reduce_college['college_id'])->first();
                //根据选择的专业和学历获取到申请要求
                $college_requirement = $college->getSpecialityRequirement($selected_speciality_name, $selected_degree);
                $res['college'] = $college->toArray();
                $res['requirement'] = $college_requirement;
                $result[$reduce_college_key][] = $res;
            }
        }
        return $result;
    }

    //根据院校名称获取院校的类型 985 211或者双非
    public static function getRecentlyCollegeType($college_name){
        if(in_array($college_name, Setting::get('985list'))){
            return '985';
        }else if(in_array($college_name, Setting::get('211list'))){
            return '211';
        }else{
            return '双非';
        }
    }
}
