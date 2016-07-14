<div class="home-slider">
    <div class="user-info">
        <div class="user-avatar">
            <img src="{{ $user->getAvatarPath() }}" />
        </div>
        <div class="user-username">
            <span>{{ $user->username }}</span>
        </div>
    </div>
    <ul>
        <li><a href="{{route('home.index')}}">我的资料</a></li>
        <li><a href="{{ route('home.messages') }}">我的消息</a></li>
        <li><a>我的收藏</a></li>
        <li><a>我的意向单</a></li>
    </ul>
</div>