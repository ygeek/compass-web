<div class="home-slider">
    <div class="user-info">
        <div class="logout">
          <a href="{{ route('auth.logout_user') }}">退出</a>
        </div>
        <div class="user-avatar">
            <img src="{{ app('auth')->user()->getAvatarPath() }}" />
        </div>
        <div class="user-username">
            <span>{{ app('auth')->user()->username }}</span>
        </div>
    </div>
    <ul>
        <li @if($active == 'index') class="active" @endif><a href="{{route('home.index')}}">我的资料</a></li>
        <li @if($active == 'message') class="active" @endif><a href="{{ route('home.messages') }}">我的消息</a></li>
        <li @if($active == 'like_college') class="active" @endif><a href="{{ route('home.like_colleges') }}">我的收藏</a></li>
        <li @if($active == 'intention') class="active" @endif><a href="{{ route('home.intentions') }}">我的意向单</a></li>
    </ul>
</div>
