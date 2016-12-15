<div class="main04">
    <div class="yxpaiming01" style="margin:0 0 1px 0;"><a href="/estimate/step-1">测试录取率</a></div>

    <div class="yuanxiao_gl">
        @foreach($articles as $article)
        <p><a href="{{ $article->link() }}" target="_blank">{{ $article->title }}</a><span>{{ $article->created_at->format('Y-m-d') }}</span></p>
       @endforeach

    </div>

    <div class="clear"></div>
</div>