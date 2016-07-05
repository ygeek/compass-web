<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialityCategory extends Model
{
    public function specialities(){
        return $this->hasMany(Speciality::class, 'category_id');
    }
}
