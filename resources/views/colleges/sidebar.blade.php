<div class="college-sidebar">
    <div class="tab">
        <!--<a href="javascript:void(0)">同城院校</a>-->
        <a class="active" href="javascript:void(0)">热门院校</a>
    </div>

    <?php $hot_colleges = App\College::where('hot', true)->get(); ?>

    @foreach($hot_colleges as $college)
    <div class="college-single">
        <img src=""/>
        <div class="separator"></div>
        <div class="row">
            <span class="name left">{{$college->chinese_name}}</span>
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