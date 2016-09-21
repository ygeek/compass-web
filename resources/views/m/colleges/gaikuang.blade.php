<div class="yuanxiao_pm01">
    <h1>院校排名</h1>
    <a href="javascript:showPm()"><img src="/static/images/yuanxi_paim.jpg"></a>
    <div class="clear"></div>
</div>

@foreach($articles as $article)
<div class="yuanxiao_jj">
    <div class="yuanxiao_jj_name">{{ $article->title }}</div>
    <div class="yuanxiao_jj_m">
      {{ html_entity_decode($article->content) }}
    </div>
</div>
@endforeach

<div class="main05">
    <div class="topPic">
        <div class="imgSlideMain">
            <div id="imgSlide"  class="imgSlide">
                <ul style="list-style: none; margin: 0px; width:100%;">
                    <li style="width:100%; display: table-cell; vertical-align: top; ">
                        <a href="#"><img src="/static/images/img01.jpg"></a></li>
                    <li style="width:100%; display: table-cell; vertical-align: top; ">
                        <a href="#"><img src="/static/images/img01.jpg"></a></li>
                    <li style="width:100%; display: table-cell; vertical-align: top; ">
                        <a href="#"><img src="/static/images/img01.jpg"></a></li>
                </ul>
            </div>
            <ul class="navSlide">
                <li class="i_point active"></li>
                <li class="i_point"></li>
                <li class="i_point"></li>
                <li class="i_point"></li></ul>
        </div>
    </div>
</div>