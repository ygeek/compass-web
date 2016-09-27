<div class="yuanxiao_pm01">
    <h1>院校排名</h1>
    <a href="javascript:showPm()"><img src="/static/images/yuanxi_paim.jpg"></a>
    <div class="clear"></div>
</div>

@foreach($articles as $article)
<div class="yuanxiao_jj">
    <div class="yuanxiao_jj_name">{{ $article->title }}</div>
    <div class="yuanxiao_jj_m">
      <?php echo  html_entity_decode($article->content); ?>
    </div>
</div>
@endforeach
<link type="text/css" href="/static/swiper/swiper.min.css" rel="stylesheet" />

<script type="text/javascript" src="/static/swiper/swiper.min.js"></script>
<!--
<div class="main05">
    <div class="topPic swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><img src="/static/images/img01.jpg" width="100%"></div>
            <div class="swiper-slide"><img src="/static/images/img01.jpg"  width="100%"></div>
            <div class="swiper-slide"><img src="/static/images/img01.jpg"  width="100%"></div>
            
        </div>
       
        <div class="swiper-pagination"></div>
    
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>
-->
<?php
    $hot_colleges = App\College::where('hot', true)->get();
    $local_colleges=[];
    if(isset($college)){
        $local_colleges = App\College::where('administrative_area_id', $college['administrative_area_id'])->where('id','<>', $college['id'])->get();
    }
    
  
?>
<div class="main05" >
    <div class="tab" >
        <div class="tabli" show="tabli1"><a  class=" act">热门院校</a></div>
        <div class="tabli"  show="tabli2"><a  class="">同城院校</a></div>
        <div class="clear"></div>
    </div>
    <div  class="sc tabli1 swiper-container" >
        <div class="swiper-wrapper">
            @foreach($hot_colleges as $college)
            <div class="swiper-slide"><img src="{{app('qiniu_uploader')->pathOfKey($college->background_image_path)}}" ></div>
            @endforeach
        </div>
       
    </div>
    <div  class="sc tabli2 swiper-container2" style="overflow: hidden; display: none;" >
        <div class="swiper-wrapper">
             @foreach($hot_colleges as $college)
            <div class="swiper-slide"><img src="{{app('qiniu_uploader')->pathOfKey($college->background_image_path)}}" ></div>
            @endforeach
        </div>
      
    </div>
</div>
<script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
       // nextButton: '.swiper-button-next',
       // prevButton: '.swiper-button-prev',
        slidesPerView: 3,
        paginationClickable: true,
        centeredSlides: true,
        spaceBetween: 30,
         autoplay: 2500,
      
        freeMode: true
    });
   var swiper2 = new Swiper('.swiper-container2', {
        pagination: '.swiper-pagination2',
       // nextButton: '.swiper-button-next',
       // prevButton: '.swiper-button-prev',
        slidesPerView: 3,
        paginationClickable: true,
        centeredSlides: true,
        spaceBetween: 30,
         autoplay: 2500,
      
        freeMode: true
    });
$(function() {
    //选择国家
    $(".tabli").click(function(){
        var tablinum = $(this).attr("show");
        $(".sc").hide();
        $("."+tablinum).show();
        $(this).children("a").addClass("act");
        $(".tabli").not(this).children("a").removeClass("act");
    })
})
</script>