<div class="college-list-articles">
    @foreach($articles as $article)
    <div class="article">
        <span class="identity">•</span>

        <a href="{{ $article->link() }}" target="_blank">
            <h1>{{ $article->title }}</h1>
        </a>

        <span class="created_at">{{ $article->created_at->format('Y-m-d') }}</span>
    </div>
    @endforeach
</div>