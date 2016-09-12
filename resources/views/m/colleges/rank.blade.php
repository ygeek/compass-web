
@include('m.public.header')
<div class="clear"></div>

<div class="main06">
    <div class="yuanxiao_pm6">
        <ul>
            @foreach($colleges as $key=>$college)
            <li @if($key%2==1)class="yuanxiao_white"@endif><h1>{{ $college['rank'] }}</h1><h2>{{ $college['chinese_name'] }}</h2><h3>{{ $college['english_name'] }}</h3><span><a href="{{route('colleges.show', ['key' => \App\College::generateKey($college['key']) ])}}">排名</a></span><div class="clear"></div></li>
            @endforeach
        </ul>
        <div class="yuyanxiao_gxl">
            <a href="#" style="width:10%;"><img src="/static/images/icon36.png"></a>
            <a id="yuyanxiao_gxl" href="#">U.S.News排名</a>
            <a href="#">QS排名</a>
            <a href="#">Times排名</a>
            <div class="clear"></div>
        </div>
    </div>
</div>


