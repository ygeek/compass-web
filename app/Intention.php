<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intention extends Model
{
    protected $casts = [
        'requirement_contrast' => 'array'
    ];

    protected $fillable = [
      'user_id', 'degree_id', 'college_id', 'speciality_name', 'student_name', 'requirement_contrast'
    ];
}
