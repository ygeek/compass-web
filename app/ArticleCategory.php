<?php

namespace App;
use Overtrue\Pinyin\Pinyin;

use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
        public static function boot(){
        parent::boot();

        static::saving(function($category)
        {
            $pinyin = new Pinyin();
            $key = $pinyin->permalink($category->name);
            $category->key = $key;
        });
    }
}
