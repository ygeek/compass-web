<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CollegeTest extends TestCase
{
    use DatabaseMigrations;

    protected $map;
    protected $weight;
    protected $college;
    protected $examinations;

    public function setUp()
    {
        parent::setUp();

        $this->college = factory(\App\College::class)->create();
        //1.设置该学校的考试分数映射表

        //创建考试
        $this->examinations = collect(['高考', '雅思', '托福'])->map(function($examination_name){
            return \App\Examination::create(['name' => $examination_name]);
        });

        //设置映射表
        $this->map = new \App\CollegeExaminationScoreMap();
        $this->map->map = [
            $this->examinations[0]->id =>
                [
                    'score_sections' => [
                        [
                            'section' => '北京:>=500',
                            'score' => 100
                        ],
                        [
                            'section' => '北京:<500',
                            'score' => 50
                        ],
                        [
                            'section' => '江西:>=510',
                            'score' => 100,
                        ],
                        [
                            'section' => '江西<510',
                            'score' => 50
                        ]
                    ]
                ],
            $this->examinations[1]->id =>
                [
                    'score_sections' => [
                        [
                            'section' => '9',
                            'score' => 100
                        ],
                        [
                            'section' => '8.5',
                            'score' => 90
                        ],
                        [
                            'section' => '8',
                            'score' => 80
                        ],
                        [
                            'section' => '4.5-7.5',
                            'score' => 60,
                        ],
                        [
                            'section' => '0-4',
                            'score' => 0,
                        ]
                    ]
                ],
            $this->examinations[2]->id =>
                [
                    'score_sections' => [
                        [
                            'section' => '>117',
                            'score' => 100
                        ],
                        [
                            'section' => '115-117',
                            'score' => 90
                        ],
                        [
                            'section' => '110-114',
                            'score' => 80
                        ],
                        [
                            'section' => '32-109',
                            'score' => 60,
                        ],
                        [
                            'section' => '<=31',
                            'score' => 0,
                        ]
                    ]
                ]
        ];
        $this->map->college()->associate($this->college);
        $this->map->save();

        //创建考试比例映射表
        $this->weight = new App\ExaminationScoreWeight();
        $this->weight->weights = [
            $this->examinations[0]->id => [
                'weight' => 20

            ],
            $this->examinations[1]->id => [
                'weight' => 40,
            ],
            $this->examinations[2]->id =>
                [
                    'weight' => 40
                ]
        ];

        $this->weight->save();
        $this->college->examinationScoreWeight()->associate($this->weight);
    }

    //测试将Map和Weight合并
    public function testMergeMap(){
        $mergedMap = \App\College::mergeMap($this->map->map, $this->weight->weights);
        $this->assertEquals(count($this->map->map), count($mergedMap));
        foreach ($mergedMap as $map){
            $this->assertTrue(!!$map['weight']);
        }
    }

    //计算学校的最终得分
    public function testCalculateScoreOfACollege(){
        //考生入场
        $studentScore = [
            [
                'examination_id' => $this->examinations[0]->id, //高考
                'score' => '北京:414', //50 * 0.2
            ],
            [
                'examination_id' => $this->examinations[1]->id, //雅思
                'score' => '6.5', //60 * 0.4
            ],
            [
                'examination_id' => $this->examinations[2]->id, //托福
                'score' => '112', //80 * 0.4
            ],
        ];

        $this->assertEquals(66, $this->college->calculateWeightScore($studentScore));
    }
}
