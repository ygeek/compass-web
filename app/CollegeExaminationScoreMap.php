<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollegeExaminationScoreMap extends Model
{

    protected $casts = [
       'map' => 'array',
    ];

    public function college(){
      return $this->belongsTo(College::class);
    }
}
