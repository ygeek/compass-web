<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExaminationScoreWeight extends Model
{
    protected $fillable = [
        'country_id', 'degree_id', 'weights', 'name'
    ];
    
    protected $casts = [
        'weights' => 'array',
    ];

    public function country(){
        return $this->belongsTo(AdministrativeArea::class);
    }

    public function degree(){
        return $this->belongsTo(Degree::class);
    }

    public function colleges(){
        return $this->belongsToMany(College::class, 'college_degree', 'examination_score_weight_id');
    }
}
