<?php
    $galleries = $articles->map(function($article){
        return $article->toGallery();
    });
?>

<div class="main04">
    <div class="yxpaiming01" style="margin:0 0 1px 0;"><a href="#">测试录取率</a></div>

    <div class="yuanxiao_pic">
        @foreach($galleries as $gallery)
       <?php $gallery = objToArr($gallery);  ?>
        <a href="#">
            <img src="{{ $gallery['images']['0'] }}">
            <h1>{{ $gallery['name'] }}</h1>
        </a>
        @endforeach
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
