<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    protected $fillable = [
        'chinese_name',
        'english_name',
        'description',
        'telephone_number',
        'founded_in',
        'address',
        'website',
        'qs_ranking',
        'us_new_ranking',
        'times_ranking',
        'domestic_ranking',
        'badge_path',
        'background_image_path',
        'administrative_area_id',
    ];

    public function administrativeArea(){
      return $this->belongsTo(AdministrativeArea::class);
    }
}
