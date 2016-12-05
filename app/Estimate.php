<?php
namespace App;

class Estimate{
    public static function grabStudentScoreFromEstimateData($estimate_data) {
      $examinations = $estimate_data['examinations'];
      $student_scores = [];
      foreach ($examinations as $examination_name => $value) {
          //前端没有提交分数 Continue
          if(!$value['score'] || $value['score'] == ''){
              continue;
          }

          $examination = Examination::where('name', $examination_name)->first();
          $item = [
              'examination_id' => $examination->id
          ];

          if($examination->multiple_degree){
              $item[$value['degree'].':score'] = $value['score'];
          }else{
              $item['score'] = $value['score'];
          }

          $student_scores[] = $item;
      }

      return $student_scores;
    }

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
    public static function mapCollegeInfo($reduce_result, $selected_speciality_name, $selected_degree, $data){
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
                $res = array_merge($res, self::grabCollegeRequirementInfo($college, $data, $selected_speciality_name, $selected_degree));
                $result[$reduce_college_key][] = $res;
            }
        }

        return $result;
    }

    public static function grabCollegeRequirementInfo($college, $data, $selected_speciality_name, $selected_degree){
      $res = [];

      //根据选择的专业和学历获取到申请要求
      $college_requirement = $college->getSpecialityRequirement($selected_speciality_name, $selected_degree);
      $res['college'] = $college->toArray();
      $res['requirement'] = $college_requirement;

      //获取 托福/雅思的需求分
      $ielts_requirement = collect($college_requirement['requirement'])->filter(function ($require){
          return $require['examination_name'] == '雅思';
      })->first()['requirement'];

      $toefl_requirement = collect($college_requirement['requirement'])->filter(function ($require){
          return $require['examination_name'] == '托福IBT';
      })->first()['requirement'];

      $res['toefl_requirement'] = $toefl_requirement;
      $res['ielts_requirement'] = $ielts_requirement;

      $res['requirement_contrast'] = self::grabContrastFromRequirement($college_requirement['requirement'], $data);
      return $res;
    }

    //根据院校名称获取院校的类型 985 211或者双非
    public static function getRecentlyCollegeType($college_name){
        if(in_array($college_name, Setting::get('985list', []))){
            return '985';
        }else if(in_array($college_name, Setting::get('211list', []))){
            return '211';
        }else{
            return '双非';
        }
    }
    //requirement 院校或专业的申请要求
    //data 用户提交的数据
    public static function grabContrastFromRequirement($requirement, $data){
        $contrasts = [];
        $examinations = collect(config('examinations'));

        foreach ($requirement as $require){
            $user_score = null;
            //当前需求的考试信息
            $examination = $examinations->where('name', $require['examination_name'])->first();

            $current_item = null;

            //判断需求客户是否提交了 如果没提交就不需要进行对比 is_requirement的除外
            if(isset($examination['form_key'])){
                $key = $examination['form_key'];
                $current_item = $data[$key];
                $user_score = $current_item;
            }else{
                $key = $require['examination_name'];
                if(!isset($examination['is_requirement'])){
                    //如果该需求用户没有提交 跳过
                    try{
                        $current_item = $data['examinations'][$key];
                        $user_score = $current_item['score'];
                    }catch (\Exception $e){
                        continue;
                    }
                }else{
                    //备注
                }
            }

            //需要从分数段中取出匹配的Tag
            if($require['tagable']){

                $tag = $current_item['tag'];
                $tag_requirement = collect($require['requirement'])->filter(function($item) use ($tag){
                    return $item['tag_name'] == $tag;
                })->first()['tag_value'];

                $contrast_user_score = $user_score;
                if(strpos($user_score, ':') !== false){
                    $contrast_user_score = explode(":", $user_score)[1];
                }

                $contrasts[] = [
                    'name' => $require['examination_name'] ."（" .$tag . "）",
                    'require' => $tag_requirement,
                    'user_score' => $contrast_user_score
                ];

            }else{
                $contrasts[] = [
                    'name' => $require['examination_name'],
                    'require' => $require['requirement'],
                    'user_score' => $user_score
                ];
            }

            //如果是有子成绩的考试 展开子成绩
            if(!!$require['sections']){
                $user_section_for_this_require = $current_item['sections'];

                foreach ($require['sections'] as $require_section){
                    $name = $require_section['name'];
                    $section_require = $require_section['requirement'];

                    $user_score_for_this_section = collect($user_section_for_this_require)->filter(function ($section) use ($name){
                        return $section['name'] == $name;
                    })->first()['score'];

                    $contrasts[] = [
                      'name' => $name,
                        'require' => $section_require,
                        'user_score' => $user_score_for_this_section
                    ];
                }
            }
        }
        return $contrasts;
    }
}
