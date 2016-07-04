<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class College extends Model
{

    protected $casts = [
      'requirement' => 'array'
    ];

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


    public static function boot(){
        parent::boot();

        static::saving(function($college)
        {
            $area_id = $college->administrative_area_id;
            $area = AdministrativeArea::find($area_id);
            if($area->isRoot()){
                $college->country_id = $area->id;
            }else{
                $college->country_id = AdministrativeArea::ancestorsOf($area_id)->first()->id;
            }
        });
    }

    public function examinationScoreMapTemplate(){
        $college_examination_ids = [];
        $degrees = $this->degrees()->where('estimatable', true)->get();
        foreach ($degrees as $degree){
            $examinations = CountryDegreeExaminationMap::getExaminationsWith($this->country->name, $degree->name);

            foreach ($examinations as $examination){
                array_push($college_examination_ids, $examination['ids']);
            }
        }

        $college_examinations = Examination::whereIn('id',
            collect($college_examination_ids)->flatten()->unique()
        )->get()->toArray();

        return $college_examinations;
    }

    public function country(){
        return $this->belongsTo(AdministrativeArea::class);
    }

    public function specialities(){
        return $this->hasMany(Speciality::class);
    }

    public function degrees(){
        return $this->belongsToMany(Degree::class);
    }
    
    public function administrativeArea(){
      return $this->belongsTo(AdministrativeArea::class);
    }

    public function examinationScoreMap(){
        return $this->hasOne(CollegeExaminationScoreMap::class);
    }

    public function examinationScoreWeight(){
        return $this->belongsToMany(ExaminationScoreWeight::class, 'college_degree', 'examination_score_weight_id');
    }

    //计算最终的权重分
    public function calculateWeightScore($student_scores, $degree){
        $map = $this->examinationScoreMap->map;
        $weights = $this->examinationScoreWeight()->where('college_degree.degree_id', $degree->id)->first()->weights;


        $merged_map = self::mergeMap($map, $weights);

        $carry = 0;
        foreach ($student_scores as $student_score){
            $current_examination = $student_score['examination_id'];
            $current_examination_map = $merged_map[$current_examination];

            $current_examination_score_sections = $current_examination_map['score_sections'];
            foreach ($current_examination_score_sections as $score_section){
                $score_map_section = new ScoreMapSection($score_section['section']);
                if($score_map_section->matching($student_score['score'])){
                    //分数段查找匹配成功
                    $score_key = 'score';
                    //有多个学历的 匹配当前学历
                    if($current_examination_map['multiple_degree']){
                        $score_key = $degree->id . ":" . $score_key;
                    }
                    $current_section_score = $score_section[$score_key] * $current_examination_map['weight'] / 100;
                    $carry += $current_section_score;
                }
            }
        }
        return $carry;
    }

    public static function mergeMap($map, $weights){
        return collect($map)->map(function($item, $key) use ($weights){
            $weight = self::findExaminationFromWeight($key, $weights);
            $item['weight'] = $weight['weight'];
            return $item;
        })->filter(function($item, $key){
            return $item['weight'] > 0;
        });
    }

    private static function findExaminationFromWeight($examination_id, $weights){
        foreach ($weights as $weight){
            if(in_array($examination_id, $weight['ids'])){
                return $weight;
            }
        }
        return null;
    }
}
