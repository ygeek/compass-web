<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class College extends Model
{
    protected $fillable = [
        'chinese_name',
        'english_name',
        'description',
        'telephone_number',
        'founded_in',
        'address',
        'website',
        'qs_ranking',
        'us_new_ranking',
        'times_ranking',
        'domestic_ranking',
        'badge_path',
        'background_image_path',
        'administrative_area_id',
    ];

    public function administrativeArea(){
      return $this->belongsTo(AdministrativeArea::class);
    }

    public function examinationScoreMap(){
        return $this->hasOne(CollegeExaminationScoreMap::class);
    }

    public function examinationScoreWeight(){
        return $this->belongsTo(ExaminationScoreWeight::class);
    }

    //计算最终的权重分
    public function calculateWeightScore($student_scores){
        $map = $this->examinationScoreMap->map;
        $weights = $this->examinationScoreWeight->weights;

        $merged_map = self::mergeMap($map, $weights);


        return collect($student_scores)->reduce(function($carry, $student_score) use ($merged_map){
            $current_examination = $student_score['examination_id'];
            $current_map = $merged_map[$current_examination];

            $sections = $current_map['score_sections'];
            foreach ($sections as $score_section){
                $score_map_section = new ScoreMapSection($score_section['section']);
                if($score_map_section->matching($student_score['score'])){
                    //分数段查找匹配成功
                    $current_section_score = $score_section['score'] * $current_map['weight'] / 100;
                    return $carry + $current_section_score;
                }
            }
        });
    }

    public static function mergeMap($map, $weights){
        return collect($map)->map(function($item, $key) use ($weights){
            $item['weight'] = $weights[$key]['weight'];
            return $item;
        });
    }
}
