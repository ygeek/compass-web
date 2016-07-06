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
}
