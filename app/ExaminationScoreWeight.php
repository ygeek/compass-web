<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExaminationScoreWeight extends Model
{
    protected $casts = [
        'weights' => 'array',
    ];
}
