<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    protected $fillable = [
        'name', 'score_sections', 'sections', 'multiple_degree', 'is_requirement', 'tagable'
    ];

    protected $casts = [
        'score_sections' => 'array',
        'sections' => 'array'
    ];
}
