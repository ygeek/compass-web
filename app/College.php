<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Setting;

class College extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

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
        'hot',
        'recommendatory',
        'type',
        'go8',
        'average_enrollment',
        'international_ratio'
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

            $key = self::generateKey($college->english_name);
            $college->key = $key;
        });
    }

    public static function generateKey($english_name){
        return str_replace(' ','-',strtolower($english_name));
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
        return $this->belongsToMany(ExaminationScoreWeight::class, 'college_degree', 'college_id');
    }

    public function articles(){
        return $this->hasMany(Article::class);
    }

    //计算最终的权重分
    public function calculateWeightScore($student_scores, $degree){
        $map = $this->examinationScoreMap->map;
        $weights = $this->examinationScoreWeight()->where('college_degree.degree_id', $degree->id)->first()->weights;
        if(is_string($weights)){
            $weights = json_decode($weights, true);
        }
        $merged_map = self::mergeMap($map, $weights);
        $carry = 0;
        foreach ($student_scores as $student_score){
            $current_examination = $student_score['examination_id'];
            $current_examination_map = $merged_map[$current_examination];

            $current_examination_score_sections = $current_examination_map['score_sections'];
            foreach ($current_examination_score_sections as $score_section){
                $score_map_section = new ScoreMapSection($score_section['section']);
                //分数段查找匹配成功
                $score_key = 'score';

                //有多个学历的 匹配当前学历
                if($current_examination_map['multiple_degree']){
                    $score_key = $degree->id . ":" . $score_key;
                }
                if($score_map_section->matching($student_score[$score_key])){
                    $current_section_score = $score_section[$score_key] * $current_examination_map['weight'] / 100;
                    $carry += $current_section_score;
                    //防止重复Match多个分数段
                    continue 2;
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
            return $item['weight'] >= 0;
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

    //获取院校专业的要求信息
    public function getSpecialityRequirement($speciality_name, $degree){
        $obj = $this->specialities->where('name', $speciality_name)->first();

        $type = 'speciality';
        if(!$obj || !$obj->requirement){
            $obj = $this;
            $type = 'college';
        }

        $requirement = [];
        $iteral = $obj->requirement;

        if(is_null($iteral)) {
          $iteral = [];
        }

        foreach ($iteral as $require){
            if($require['id'] == $degree->id){
                $requirement = $require['examinations'];
            }
        }

        return [
            'type' => $type,
            'requirement' => $requirement
        ];
    }

    public function toeflRequirement($degree_name){
        $toefl_requirement = collect(collect($this->requirement)->where('name', $degree_name)->first()['examinations'])->filter(function ($require){
            return $require['examination_name'] == '托福IBT';
        })->first()['requirement'];
        return $toefl_requirement;
    }

    public function ieltsRequirement($degree_name){
        $ielts_requirement = collect(collect($this->requirement)->where('name', $degree_name)->first()['examinations'])->filter(function ($require){
            return $require['examination_name'] == '雅思';
        })->first()['requirement'];
        return $ielts_requirement;
    }

    //拦截排名get
    public function getAttribute($key){
      $ranking_fields = ['us_new_ranking', 'times_ranking', 'qs_ranking', 'domestic_ranking'];
      if(in_array($key, $ranking_fields)){
        return $this->getCollegeRanking($key);
      }else{
        return parent::getAttribute($key);
      }
    }

    //拦截toArray
    public function toArray(){
      $arr = parent::toArray();
      $ranking_fields = ['us_new_ranking', 'times_ranking', 'qs_ranking', 'domestic_ranking'];
      foreach ($ranking_fields as $ranking_field) {
        $arr[$ranking_field] = $this->getCollegeRanking($ranking_field);
      }
      return $arr;
    }

    public function getCollegeRanking($ranking_field){
      $rankings = Setting::get('rankings');
      $null_ranking = "-";
      if(!$rankings){
        return $null_ranking;
      }else{

        $tag = str_replace("_ranking", "", $ranking_field);

        //查询国内排名
        if($ranking_field == 'domestic_ranking'){
          $country = $this->country->name;
          $tag = $country;
        }

        foreach ($rankings['rankings'] as $ranking) {
          $ranking_tag = $ranking['tag'];
          if (strpos($ranking_tag, $tag) !== false) {
            //排行榜的标签包含了查询的key 说明可以使用该排行榜的数据

            $index = 0;
            foreach ($ranking['rank'] as $rank) {
              $index += 1;

              if(trim($rank['english_name']) == trim($this->english_name)){
                return $index;
              }

            }
          }
        }

        return $null_ranking;
      }
    }
}
