<?php $hot_colleges = App\College::where('hot', true)->get(); ?>

@if(count($hot_colleges) > 0 )
<div class="college-sidebar">
    <div class="tab">
        <a class="active" href="javascript:void(0)">热门院校</a>
    </div>
    @foreach($hot_colleges as $college)
    <div class="college-single">
        <a href="{{route('colleges.show', ['key' => $college->key])}}" target="_blank">
          <img src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}"/>
        </a>
        <div class="separator"></div>
        <div class="row">
          <a style="color: #6c6c6c" href="{{route('colleges.show', ['key' => $college->key])}}" target="_blank">
            <span class="name left">{{$college->chinese_name}}</span>
          </a>

            <span class="right">本国排名：{{$college->domestic_ranking}}</span>
        </div>
        <div class="row">
            <span>{{ $college->english_name }}</span>
        </div>
        <div class="row">
            <span class="left">托福：{{ $college->toeflRequirement('本科') }}</span>
            <span class="right">雅思：{{ $college->ieltsRequirement('本科') }}</span>
        </div>
    </div>
    @endforeach
</div>
@endif
