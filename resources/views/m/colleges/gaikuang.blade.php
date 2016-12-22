<div id="college-page-nav" style="margin-bottom: 60px;"></div>

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
@include('m.colleges.yuanxiao')