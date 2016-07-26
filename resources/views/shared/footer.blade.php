<div class="footer">
    <div class="app-content clear">
        <div class="logo">
            <img src="/images/slogan_ico.png" alt="logo"/>
        </div>
        <div class="text">
            <p>关于：</p>
            <?php
              $articles = App\Article::whereHas('category', function($q){
                return $q->where('key', 'guan-yu');
            })->orderBy('articles.order_weight')->limit(4)->get();
            ?>

            @foreach($articles as $article)
            <a href="{{ $article->link() }}">{{ $article->title }}</a>
            @endforeach
        </div>
        <div class="text">
            <p>友情链接：</p>
            <?php
              $articles = App\Article::whereHas('category', function($q){
                return $q->where('key', 'you-qing-lian-jie');
            })->orderBy('articles.order_weight')->limit(4)->get();
            ?>

            @foreach($articles as $article)
            <a href="{{ $article->link() }}">{{ $article->title }}</a>
            @endforeach
        </div>
        <div class="copyright">
            <div class="clear">
                <img src="/images/weibo_ico.png" alt="weibo_ico"/>
                <img src="/images/wexin_ico.png" alt="wexin_ico"/>
            </div>
            <p>Copyright © 2013-2016  京ICP备11014111号-2</p>
        </div>
    </div>
</div>
