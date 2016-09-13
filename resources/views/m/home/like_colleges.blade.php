@include('m.public.header')
<div class="clear"></div>
<div class="main02">
    @include('m.home.editUser')
    <div class="grzy_wdxx_meun">
        <span>
            <a href="{{ route('home.index') }}">消息</a>
            <a id="grzy_wdxx_meun" href="{{ route('home.like_colleges') }}" >收藏</a>
            <a href="{{ route('home.intentions') }}" >意向单</a>
        </span>
    </div>
    <div class="grzy_wdsc_list">
        <ul>
            @foreach($colleges as $college)
            <a href="{{route('colleges.show', $college->key)}}"><li>
                <img src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}">
                <h1>{{$college->chinese_name}}<br>{{$college->english_name}}</h1>
            </li></a>
            @endforeach
            

        </ul>
    </div>
    <div class="clear"></div>
</div>