<?php
/**
 * Created by PhpStorm.
 * User: chareice
 * Date: 16-6-23
 * Time: 20:42
 */

namespace App;
/*
 *
 */

class ScoreMapSection
{

    private $preset_section;

    private $section;

    private $score;

    //分数区间标签 区分高考省份
    private $tag;

    protected $operator;

    const TAG_SPLITER = ':';

    public function __construct($section)
    {
        $this->preset_section = [
            '<=' => function($score){
                return $score <= $this->score;
            },
            '>=' => function($score){
                return $score >= $this->score;
            },
            '>' => function($score){
                return $score > $this->score;
            },
            '<' => function($score){
                return $score < $this->score;
            },
            '-' => function($score){
                $res =  ($score >= $this->score[0] && $score <= $this->score[1]);
                return $res;
            },
            '=' => function($score){
                return $score == $this->score;
            }
        ];

        $this->section = $section;

        $parse_res = $this->parse($section);
        $this->score = $parse_res['score'];
        $this->tag = $parse_res['tag'];
        $this->operator = $parse_res['operator'];
    }

    public function currentClosure(){
        return $this->preset_section[$this->operator];
    }

    public function matching($tester){
        $res = $this->grabTag($tester);
        $tag = $res['tag'];
        $tester = $res['remain_section'];

        //匹配Tag
        if($tag != $this->tag){
            return false;
        }
        $closure = $this->currentClosure();
        return $closure($tester);
    }

    private function parse($section){
        $tag = null;
        $operator= null;
        $score = null;

        if($this->grabTag($section)){
            $grabbed_section = $this->grabTag($section);
            $tag = $grabbed_section['tag'];
            $section = $grabbed_section['remain_section'];
        }

        $operator = $this->grabOperator($section);
        $score = $this->grabScore($this->trimOperator($section, $operator));

        return compact('tag', 'operator', 'score');
    }

    private function grabTag($section){
        $tagAttempt = explode(self::TAG_SPLITER, $section);

        $res = [
            'tag' => null,
            'remain_section' => $section
        ];

        //tag exist
        if(count($tagAttempt) > 1){
            $res['tag'] = $tagAttempt[0];
            $res['remain_section'] = $tagAttempt[1];
        }

        return $res;
    }

    private function trimOperator($section, $operator){
        return str_replace($operator, ' ', $section);
    }

    private function grabOperator($section){
        $defaultOperation = '=';
        $allOperators = array_keys($this->preset_section);
        foreach ($allOperators as $operator){
            if (strpos($section, $operator) !== false) {
                return $operator;
            }
        }

        return $defaultOperation;
    }

    private function grabScore($section){
        $scores = collect(explode(' ', $section))->filter(function($score){
            return $score != '';
        });

        if(count($scores) == 1){
            return $scores->first();
        }else{
            return $scores;
        }
    }
}