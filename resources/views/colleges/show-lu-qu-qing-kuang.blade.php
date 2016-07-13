<div class="college-applications">

    <div class="left-grid">
        <?php $index = 0; ?>
        @foreach($articles as $article)
        @if($index % 2 == 0)
        <div class="article" >
            <header>{{ $article->title }}</header>
            <div class="article-content">
                {!! $article->content !!}
            </div>
        </div>
        @endif
        <?php $index++; ?>
        @endforeach
    </div>

    <div class="right-grid">
        <?php $index = 0; ?>
        @foreach($articles as $article)
        @if($index % 2 != 0)
        <div class="article" >
            <header>{{ $article->title }}</header>
            <div class="article-content">
                {!! $article->content !!}
            </div>
        </div>
        @endif
        <?php $index++; ?>
        @endforeach
    </div>
</div>
