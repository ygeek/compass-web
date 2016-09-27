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
<style>
.grzy_wdsc_list{padding: 10px 0px; margin: 0px;  background-color: #0e2e61;}
.grzy_wdsc_list li{width:100%; background:#fff; display:block; float:left; padding:2%; margin:0 0 2% 2%; font-size:1.2em; color:#0e2d60;}
.grzy_wdsc_list li img{width:100%;}
.grzy_wdsc_list li h1{font-weight:normal;color:#0e2d60; border-top:1px solid #ddd; padding:3% 0 2% 0;}
.grzy_wdsc_list li h1 a{color:#0e2d60;}

    </style>
<div class="main05" >
    <div class="tab" >
        <div class="tabli" show="tabli1"><a  class=" act">热门院校</a></div>
        <div class="tabli"  show="tabli2"><a  class="">同城院校</a></div>
        <div class="clear"></div>
    </div>
    <div  class=" tabli1 swiper-container grzy_wdsc_list" >
        <ul class="swiper-wrapper">
            
            @foreach($hot_colleges as $college)
            <a href="{{route('colleges.show', $college->key)}}" ><li class="swiper-slide">
                <img src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}">
                <h1>{{$college->chinese_name}}<br><font>{{$college->english_name}}</font></h1>
                </li></a>
            @endforeach
        </ul>
       
    </div>
    <div  class=" tabli2 swiper-container2 grzy_wdsc_list" style="overflow: hidden; " >
        <ul class="swiper-wrapper">
            
            @foreach($local_colleges as $college)
            <a href="{{route('colleges.show', $college->key)}}" ><li class="swiper-slide">
                <img src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}">
                <h1>{{$college->chinese_name}}<br><font>{{$college->english_name}}</font></h1>
                </li></a>
            @endforeach
        </ul>
      
    </div>
</div>
<script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
       // nextButton: '.swiper-button-next',
       // prevButton: '.swiper-button-prev',
        slidesPerView: 2,
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
        slidesPerView: 2,
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
        $(".grzy_wdsc_list").hide();
        $("."+tablinum).show();
        $(this).children("a").addClass("act");
        $(".tabli").not(this).children("a").removeClass("act");
    })
})
$(".tabli2").hide();
</script>