
@include('m.public.header')

<div class="clear"></div>
<div class="h_banner">
    <h1><a href="/estimate/step-1">我要评估</a></h1>
    <img src="/static/images/banner.jpg">
    <div class="clear"></div>

</div>
<div class="h_main">
    <div class="gxm_name01">
        <h1>留学流程</h1>
    </div>
    <div class="h_icon01">
        <span>
            <a >
                <img src="/static/images/icon01.png"><br><font>1.在线留学评估</font>
            </a>
        </span>
        <span>
            <a >
                <img src="/static/images/icon02.png"><br><font>2.专家电话复核</font>
            </a>
        </span>
        <span>
            <a >
                <img src="/static/images/icon03.png"><br><font>3.办公室签约</font>
            </a>
        </span>
        <span>
            <a >
                <img src="/static/images/icon04.png"><br><font>4.顾问线下为您<br />
                院校申请</font>
            </a>
        </span>
        <span>
            <a >
                <img src="/static/images/icon05.png"><br><font>5.顾问线下为您<br />
                办理签证</font>
            </a>
        </span>
        <span>
            <a >
                <img src="/static/images/icon06.png"><br><font>6.海外公司提供<br />
                后续服务</font>
            </a>
        </span>
    </div>
    <div class="clear"></div>
    <div class="h_message">
        <h1>
            <img src="/static/images/icon07.png">
            <a href="javascript:void(0)" onclick='easemobim.bind({tenantId: 21250})'>我要咨询</a>
        </h1>
    </div>
    <div class="clear"></div>

    <div class="tabbox" id="statetab">
        <ul class="tabbtn">

            @foreach($countries as $key=>$country)
            <li @if ($key==0) class="current" @endif ><a>{{ $country->name }}</a></li>

            @endforeach
        </ul><!--tabbtn end-->

        <div class="clear"></div>
        @foreach($countries as $country)
        <?php $index = 0; ?>
        <div class="tabcon">
            @foreach($country->children as $state)
            <?php if ($index == 7) { ?>
                <a target="_blank" href="{{ route('colleges.index', ['selected_country_id' => $country->id]) }}">更多</a>
                <?php break;
            } ?>
            <a target="_blank" href="{{ route('colleges.index', ['selected_country_id' => $country->id, 'selected_state_id' => $state->id]) }}">{{ $state->name }}</a>
<?php $index++; ?>
            @endforeach
            <div class="clear"></div>
        </div><!--tabcon end-->
        @endforeach
    </div>
    <div class="clear"></div>
    <div class="yx_chaxun">
        <form method="GET" target="_blank" action="{{ route('colleges.index') }}">
            <input type="text" class="chax_input" name="college_name" placeholder="输入院校名称" >
            <input type="hidden" class="chax_input" name="selected_country_id" value="-1" >
            <input type="submit" class="chax_so" value="">
        </form>
    </div>
    <div class="h_kemu">
        <ul>
            <li><a href="{{ route('colleges.index', ['selected_speciality_cateogry_id' => 2]) }}"><img src="/static/images/kemu_img01.jpg"><h1>法学</h1></a></li>
            <li><a href="{{ route('colleges.index', ['selected_speciality_cateogry_id' => 3]) }}"><img src="/static/images/kemu_img02.jpg"><h1>医学</h1></a></li>
            <li><a href="{{ route('colleges.index', ['selected_speciality_cateogry_id' => 4]) }}"><img src="/static/images/kemu_img03.jpg"><h1>工科</h1></a></li>
            <li><a href="{{ route('colleges.index', ['selected_speciality_cateogry_id' => 2]) }}"><img src="/static/images/kemu_img04.jpg"><h1>人文艺术</h1></a></li>
            <li><a href="{{ route('colleges.index', ['selected_speciality_cateogry_id' => 6]) }}"><img src="/static/images/kemu_img05.jpg"><h1>商科</h1></a></li>
            <li><a href="{{ route('colleges.index', ['selected_speciality_cateogry_id' => 9]) }}"><img src="/static/images/kemu_img06.jpg"><h1>经济金融</h1></a></li>
        </ul>
        <div class="clear"></div>
      <!--  <div class="kemu_more"><a href="#">更多专业</a></div>-->
    </div>
    <?php
    $more = App\Setting::get('index_more', ['#', '#', '#']);
    ?>
    <div class="clear"></div>
    <div class="yuyanxx">
        <div class="yuyanxx_m">
            <h1>语言学习</h1><em><a href="{{ $more[0] }}"  target="_blank" style="color: #1ddab0;">更多></a></em>
            <div class="clear"></div>
            <?php
            $articles = App\Article::whereHas('category', function($q) {
                    return $q->where('key', 'yu-yan-xue-xi');
                })->orderBy('articles.order_weight')->limit(7)->get();
            ?>
            @foreach($articles as $article)
            <p><a href="{{ $article->link() }}" target="_blank">{{ $article->title }}</a></p>
            @endforeach

         
        </div>
    </div>
    <div class="clear"></div>
    <div class="h_ad"><img src="/static/images/liuxuegonglue.jpg"></div>
    <div class="yuyanxx">
        <div class="yuyanxx_m">
            <h1>留学攻略</h1><em><a href="{{ $more[1] }}"  target="_blank" style="color: #1ddab0;">更多></a></em>
            <div class="clear"></div>
            <?php
            $articles = App\Article::whereHas('category', function($q) {
                    return $q->where('key', 'liu-xue-gong-lue');
                })->whereNull('college_id')->orderBy('articles.order_weight')->limit(7)->get();
            ?>
            @foreach($articles as $article)
            <p><a href="{{ $article->link() }}" target="_blank">{{ $article->title }}</a></p>
            @endforeach
          
        </div>
    </div>
    <div class="clear"></div>
    <div class="h_ad"><img src="/static/images/img01.jpg"></div>
    <div class="yuyanxx">
        <div class="yuyanxx_m">
            <h1>移民攻略</h1><em><a href="{{ $more[2] }}"  target="_blank" style="color: #1ddab0;">更多></a></em>
            <div class="clear"></div>
            <?php
            $articles = App\Article::whereHas('category', function($q) {
                    return $q->where('key', 'yi-min-gong-lue');
                })->orderBy('articles.order_weight')->limit(7)->get();
            ?>
            @foreach($articles as $article)
            <p><a href="{{ $article->link() }}" target="_blank">{{ $article->title }}</a></p>
            @endforeach
        </div>
    </div>
    <div class="clear"></div>

</div>
<script>
window.easemobim = window.easemobim || {};
easemobim.config = {
    hide: true,   //是否隐藏小的悬浮按钮
    autoConnect: true    //自动连接
};
</script>
<script src='//kefu.easemob.com/webim/easemob.js?tenantId=21250' async='async'></script>
@include('m.public.footer')

