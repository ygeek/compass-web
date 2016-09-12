<footer>
    <div class="footer_logo"><img src="/static/images/logo1.png"></div>
    <div class="footer_l">
        <dl>
            <dt><a href="javascript:void(0)">关于：</a></dt>
            <?php
            $articles = App\Article::whereHas('category', function($q) {
                    return $q->where('key', 'guan-yu');
                })->whereNull('college_id')->orderBy('articles.order_weight')->limit(4)->get();
            ?>

            @foreach($articles as $article)
            <dd><a href="{{ $article->link() }}" target="_blank">{{ $article->title }}</a></dd>
            @endforeach

        </dl>
        <dl>
            <dt><a href="javascript:void(0)">友情连接：</a></dt>
            <?php
            $articles = App\Article::whereHas('category', function($q) {
                    return $q->where('key', 'you-qing-lian-jie');
                })->whereNull('college_id')->orderBy('articles.order_weight')->limit(4)->get();
            ?>

            @foreach($articles as $article)
            <dd><a href="{{ $article->link() }}" target="_blank">{{ $article->title }}</a></dd>
            @endforeach
        </dl>
    </div>
    <div class="footer_r"><img src="/static/images/icon09.png" /><img src="/static/images/icon10.png" /></div>
    <div class="clear"></div>
    <div class="footer_bq">Copyright © 2013-2016  京ICP备11014111号-2</div>
</footer>

</div>
<script type="text/javascript" src="/static/js/jquery.js"></script>
</body>
</html>
