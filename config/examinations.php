<?php
/*
 * 标示字段说明
 * multiple_degree 是否根据学历不同而需要不同处理
 * is_requirement 是否是申请要求 default false
 * examination_only 要求Only
 * computed 是推导出的
 * */

return [
    [
        'name' => '雅思',
        'sections' => [
            '听', '说', '读', '写'
        ],
        'score_sections' => [
            '9', '8.5', '8', '7.5', '7', '6.5', '6', '5.5', '5', '4.5', '0-4'
        ],
        'multiple_degree' => true
    ],
    [
        'name' => '托福IBT',
        'sections' => [
            '听', '说', '读', '写'
        ],
        'score_sections' => [
            '118-120', '115-117', '110-114', '102-109', '94-101', '79-93', '60-78', '46-59', '35-45', '32-34', '0-31'
        ],
        'multiple_degree' => true
    ],
    [
        'name' => '大学平均成绩',
        'score_sections' => [
            '90-100', '85-89', '80-84', '75-79', '70-74', '65-69', '60-78', '60-54', '<=59'
        ],
        'tagable' => true
    ],
    [
        'name' => '高中平均成绩',
        'score_sections' => [
            '90-100', '85-89', '80-84', '75-79', '70-74', '65-69', '60-78', '60-54', '<=59'
        ],
        'tagable' => false
    ],
    [
        'name' => '高考',
        'score_sections' => [
            '北京:>=500', '北京:<500'
        ],
        'tagable' => true,
    ],
    [
        'name' => 'ACT',
        'sections' => [
            '作文'
        ],
    ],
    [
        'name' => 'SAT',
        'sections' => [
            '阅读', '写作', '数学'
        ],
    ],
    [
        'name' => 'GRE',
        'sections' => [
            '语文', '数学', '写作'
        ],
    ],
    [
        'name' => 'GMAT',
        'sections' => [
            '语文', '数学', '写作'
        ],
    ],
    [
        'name' => '相关专业工作年限',
        'is_requirement' => true
    ],
    [
        'name' => '院校性质',
        'score_sections' => ['985', '211', '双非'],
        // 'examination_only' => true
    ]
];