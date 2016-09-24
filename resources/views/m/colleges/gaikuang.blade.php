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
<div class="main05">
    <div class="topPic swiper-container">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><img src="/static/images/img01.jpg" width="100%"></div>
            <div class="swiper-slide"><img src="/static/images/img01.jpg"  width="100%"></div>
            <div class="swiper-slide"><img src="/static/images/img01.jpg"  width="100%"></div>
            
        </div>
         <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Arrows
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div> -->
    </div>
</div>

<script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
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