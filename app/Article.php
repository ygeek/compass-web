<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function category(){
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    //从content中抽出image地址
    public function images(){
        $doc = new \DOMDocument();
        $doc->loadHTML($this->content);
        $tags = $doc->getElementsByTagName('img');
        $images = [];
        foreach ($tags as $tag) {
           $images[] = $tag->getAttribute('src');
        }
        return collect($images);
    }

    public function toGallery(){
        $name = $this->title;
        $imaegs = $this->images();
        return ['name' => $name, 'images' => $imaegs];
    }
}
