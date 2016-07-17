<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intention extends Model
{
    protected $casts = [
        'data' => 'array'
    ];

    protected $fillable = [
    ];
}
