@include('m.public.header')
<script type="text/javascript" src="/statics/js/index.js"></script>
<script type="text/javascript" src="/statics/js/scroll.js"></script>
<script type="text/javascript" src="/statics/js/scrollload.js"></script>

<style>
    #liked { background: url(/static/images/icon24_1.jpg) center no-repeat;}
</style>
<div class="xyxiangqing">

    <div class="clear"></div>
    <div class="n_banner">
        <h1><a href="javascript:void(0)" @if($college->liked=='1') id="liked" @endif><img style="width:40px; height: 40px; margin: 15px auto;" height="40" src="/static/images/xin<?php if(app('auth')->user()){ if($college->liked=='1'){echo 1;} else {echo 2;}}else{echo 2;} ?>.png" width="30" style=" cursor: pointer;" likeid='<?php if(app('auth')->user()){ if($college->liked=='1'){echo 1;} else {echo 2;}}else{echo 3;} ?>' onclick="setLike('{{ $college->id }}',$(this))" ></a></h1>
        <img src="{{app('qiniu_uploader')->pathOfKey($college->background_image_path)}}">
        
        <div class="clear"></div>

    </div>
    <div class="pinggu_xx01" >
        <div class="pinggu_xx_name01">
            <img src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}">
            <h1>{{$college->chinese_name}}<br>{{$college->english_name}}</h1>
            <div class="clear"></div>
        </div>
        <div class="yuanxiao_pm50">
            <ul>
                <li style=" margin-left: 2%;">
                      <h1 class="color01">{{$college->qs_ranking}}</h1>
                      <span>QS排名</span>
                 </li>
                  <li>
                      <h1 class="color02">{{$college->us_new_ranking}}</h1>
                      <span>US New排名</span>
                 </li>
                  <li>
                      <h1 class="color03">{{$college->times_ranking}}</h1>
                      <span>Times排名</span>
                 </li>
                  <li>
                      <h1 class="color04">{{$college->domestic_ranking}}</h1>
                      <span>国内排名</span>
                 </li>
                 <div class="clear"></div>
            </ul>
        </div>
       
        <div class="yuanxiao_xx">
            <p style="float:left; width:60%"><img src="/static/images/icon21.jpg"><span>{{$college->administrativeArea->name}} · since {{$college->founded_in}}</span></p>
            <p style="float:left; width:40%"><img src="/static/images/icon22.jpg"><span>{{$college->telephone_number}}</span></p>
            <div class="clear"></div>
            <p><img src="/static/images/icon23.jpg"><a href="{{$college->website}}"><span style=" margin-left: 3%;">{{$college->website}}</span></a></p>
       </div>
       
        <div class="clear"></div>
    </div>
    <!--院校概况-->

    @if($article_key == 'xue-xiao-gai-kuang')
    @include('m.colleges.gaikuang')
    @endif
    
    <!--录取情况-->
    @if($article_key == 'lu-qu-qing-kuang')
    @include('m.colleges.luqu')
    @endif
    
    <!--zhuanye-->
    @if($article_key == 'specialities')
    @include('m.colleges.zhuanye')
    @endif
  
    @if($article_key == 'tu-pian')
    @include('m.colleges.tupian')
    @endif
    
    @if($article_key == 'liu-xue-gong-lue')
    @include('m.colleges.gonglue')
    @endif
    <div class="footer_top"></div>
    <div class="footer01" style="z-index:999; height: 120px;">
        <ul>
            <li class="home01"><a href="{{ route('colleges.show', ['key' => $college->key]) }}#college-page-nav" @if($article_key == 'xue-xiao-gai-kuang') id="current" @endif  >学校概况</a></li>
            <li class="home02"><a href="{{ route('colleges.show', ['key' => $college->key, 'article_type' => 'lu-qu-qing-kuang']) }}#college-page-nav" @if($article_key == 'lu-qu-qing-kuang') id="current" @endif >录取情况</a></li>
            <li class="home03"><a href="{{ route('colleges.show', ['key' => $college->key, 'article_type' => 'specialities']) }}#college-page-nav" @if($article_key == 'specialities')id="current"@endif >专业</a></li>
            <li class="home04"><a href="{{ route('colleges.show', ['key' => $college->key, 'article_type' => 'tu-pian']) }}#college-page-nav" @if($article_key == 'tu-pian')id="current"@endif >图片</a></li>
            <li class="home05"><a href="{{ route('colleges.show', ['key' => $college->key, 'article_type' => 'liu-xue-gong-lue', 'desc' => '1']) }}#college-page-nav" @if($article_key == 'liu-xue-gong-lue')id="current"@endif>留学攻略</a></li>
        </ul>
        <a href="/estimate/step-1"><div style=" height: 40px; line-height: 40px; color: #fff; background-color: #1ddab0; text-align: center; position: fixed; bottom: 0px; left: 0px; right: 0px; max-width: 800px; margin: 0 auto;">开启免费评估</div></a>
    </div>

</div>

<div class="yxpaiming" style="display: none" >
    <div class="header">
        <a href="javascript:gobackCel();"><div class="header_l"><img src="/static/images/back.png" height="20" /></div></a>
        <div class="header_c">本校排名</div>
    </div>
    <div class="clear"></div>
    <div class="main02">
        <div class="yuanxiao_pm02">
            <ul>
                <li>
                    <h1 class="color01">{{$college->qs_ranking}}</h1>
                    <span>QS排名</span>
                </li>
                <li>
                    <h1 class="color02">{{$college->us_new_ranking}}</h1>
                    <span>US New排名</span>
                </li>
                <li>
                    <h1 class="color03">{{$college->times_ranking}}</h1>
                    <span>Times排名</span>
                </li>
                <li>
                    <h1 class="color04">{{$college->domestic_ranking}}</h1>
                    <span>国内排名</span>
                </li>
                <div class="clear"></div>
            </ul>
        </div>
        <div class="clear"></div>
        <div class="yuanxiao_pm_name"><h1>院校排名</h1></div>
        <div class="clear"></div>
        <?php
            $rankings = App\Setting::get('rankings');
            function echoRank($rankings){
                foreach ($rankings as $ranking){
                    if (isset($ranking['checked'])){
                        echo "<div class=\"yxpaiming01\"><a href='".route('colleges.rank', ['category_id' => $ranking['_id']])."' class='level-2'  target='_blank'>".$ranking['name']."</a></div>";
                    }
                    if (count($ranking['children'])>0){
                        echoRank($ranking['children']);
                    }
                }
            }
            ?>
         <?php echoRank($rankings['categories']); ?>
        
       
        <div class="clear"></div>
    </div>
</div>
<script>
function shoucang(num)
{
    $.ajax({
        type:'POST',
        url:'/like_college',
        data:'college_id='+num,
        async:false,
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
        dataType:'json',
        success:function(e){
            if(e.status == 'ok'){
                alert('收藏成功!');
            }

        },
        error:function(){
            @if(Auth::check())
                 alert('请求失败!');
            @else
                 //alert('请先登录!');
                 changeView('#login');
            @endif
        }
    });
}
</script>