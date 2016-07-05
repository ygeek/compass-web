<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    protected $casts = [
      'requirement' => 'array'
    ];
    
    protected $fillable = [
        'name', 'category_id', 'degree_id'
    ];

    public function country(){
        $college = $this->college();
        return $college->getResults()->country();
    }

    public function college(){
        return $this->belongsTo(College::class);
    }

    public function degree(){
        return $this->belongsTo(Degree::class);
    }

    public function category(){
        return $this->belongsTo(SpecialityCategory::class, 'category_id');
    }
}
