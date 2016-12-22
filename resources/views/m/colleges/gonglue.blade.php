<div id="college-page-nav" style="margin-bottom: 50px;"></div>
<div class="main04">
  
    <div class="yuanxiao_gl">
        @foreach($articles as $article)
        <p><a href="{{ $article->link() }}" target="_blank">{{ $article->title }}</a><span>{{ $article->created_at->format('Y-m-d') }}</span></p>
       @endforeach

    </div>

    <div class="clear"></div>
</div>
@include('m.colleges.yuanxiao')