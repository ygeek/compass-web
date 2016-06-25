<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExaminationScoreWeight extends Model
{
    protected $fillable = [
        'country_id', 'degree_id', 'weights'
    ];
    
    protected $casts = [
        'weights' => 'array',
    ];
}
