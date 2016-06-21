<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;


class AdministrativeArea extends Model
{
    use NodeTrait;

    protected $fillable = ['name'];
}
