<div class="footer">
    <div class="app-content clear" style="width: 1150px;">
        <div class="logo">
            <img src="/images/slogan_ico.png" alt="logo"/>
        </div>
        <div class="text">
            <p>关于：</p>
            <?php
              $articles = App\Article::whereHas('category', function($q){
                return $q->where('key', 'guan-yu');
            })->whereNull('college_id')->orderBy('articles.order_weight')->limit(4)->get();
            ?>

            @foreach($articles as $article)
            <a href="{{ $article->link() }}" target="_blank">{{ $article->title }}</a>
            @endforeach
        </div>
        <div class="text">
            <p>友情链接：</p>
            <?php
              $articles = App\Article::whereHas('category', function($q){
                return $q->where('key', 'you-qing-lian-jie');
            })->whereNull('college_id')->orderBy('articles.order_weight')->limit(4)->get();
            ?>

            @foreach($articles as $article)
            <a href="{{ $article->link() }}" target="_blank">{{ $article->title }}</a>
            @endforeach
        </div>
        <div class="copyright">
            <div class="clear">
                <img src="/images/weibo_code.png" alt="weibo_ico"/>
                <span>微博</span>
                <img src="/images/weixin_code.jpg" alt="wexin_ico" style="margin-left: 40px"/>
                <span style="margin-right: 0;">微信</span>
            </div>
            <p style="text-align: center;">Copyright © 2013-2016  京ICP备11014111号-2</p>
        </div>
    </div>
</div>
