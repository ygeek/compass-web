<?php
    $galleries = $articles->map(function($article){
        return $article->toGallery();
    });
?>

<div class="main04">
    <div class="yxpaiming01" style="margin:0 0 1px 0;"><a href="#">测试录取率</a></div>
    
    <div class="yuanxiao_pic">
        @foreach($galleries as $key=>$gallery)
       <?php $gallery = objToArr($gallery);  ?>
      
        <a href="javascript:void(0)" onclick="lb('{{$key}}')">
            <img src="{{ $gallery['images']['0'] }}">
            <h1>{{ $gallery['name'] }}</h1>
        </a>
        @endforeach
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<style>
    .lunbo { position: fixed; display: none; top: 0px; left: 0px; width: 100%; height: 100%; z-index: 9999;
opacity:0.9; background-color: #000; }
</style>
<link type="text/css" href="/static/swiper/swiper.min.css" rel="stylesheet" />
<script type="text/javascript" src="/static/swiper/swiper.min.js"></script>
@foreach($galleries as $key=>$gallery)
<?php $gallery = objToArr($gallery);  ?>
<div class="lunbo swiper-container{{$key}}" style=" position: fixed; z-index: 10000;">
    <div class="swiper-wrapper">
        @foreach($gallery['images'] as $val)
        <div class="swiper-slide"><img src="{{$val}}" width="100%"></div>
        @endforeach

    </div>
     <!-- Add Pagination -->
    <div class="swiper-pagination{{$key}}"></div>
</div>
<script>
    var swiper{{$key}} = new Swiper('.swiper-container{{$key}}', {
        pagination: '.swiper-pagination{{$key}}',
       // nextButton: '.swiper-button-next',
       // prevButton: '.swiper-button-prev',
        slidesPerView: 1,
        paginationClickable: true,
        centeredSlides: true,
        spaceBetween: 30,
         autoplay: 2500,
        loop: true
    });
</script>
@endforeach

<script>
   function lb(con){
       $(".lunbo").hide();
       $(".swiper-container"+con).show();
   } 
</script>