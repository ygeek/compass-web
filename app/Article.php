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
        return $this->getNodesAttrFormHtml($this->content, 'img', 'src');
    }

    public function link(){
        preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $this->content, $match);

        try {
          $links = $match[0];
          return $links[0];
        } catch (\Exception $e) {
          return '';
        }
    }

    public function toGallery(){
        $name = $this->title;
        $imaegs = $this->images();
        return ['name' => $name, 'images' => $imaegs];
    }

    private function getNodesAttrFormHtml($html, $tag, $attr){
        $doc = new \DOMDocument();
        $doc->loadHTML($html);
        $tags = $doc->getElementsByTagName($tag);
        $attrs = [];
        foreach ($tags as $tag) {
           $attrs[] = $tag->getAttribute($attr);
        }
        return collect($attrs);
    }
}
