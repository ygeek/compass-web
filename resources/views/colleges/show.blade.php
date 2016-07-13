@extends('layouts.app')

@section('content')
    <div class="college-page">
        <div class="college-page-header" style="background-image: url({{app('qiniu_uploader')->pathOfKey($college->background_image_path)}});">
            <div class="app-content">
                @include('shared.top_bar')
                <div class="college-name">
                    <h1>{{$college->english_name}}</h1>

                    <p>
                        {{$college->description}}
                    </p>
                </div>
            </div>
        </div>

        <div class="college-page-content">
            <div class="app-content">
                <div class="college-content-header">
                    <img class="college-logo" src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}" alt="logo" />
                    <div class="college-info">
                        <h1>{{$college->chinese_name}}</h1>
                        <h2>{{$college->english_name}}</h2>
                        <div class="location info">
                            <div class="img-container">
                                <img src="/images/location-identity.png" alt="location-identity" />
                            </div>
                            <div class="info-content">
                                {{$college->administrativeArea->name}} · since {{$college->founded_in}}
                            </div>
                        </div>
                        <div class="phone info">
                            <div class="img-container">
                                <img src="/images/phone-identity.png" alt="phone-identity" />
                            </div>
                            <div class="info-content">
                                {{$college->telephone_number}}
                            </div>
                        </div>
                        <div class="website info">
                            <div class="img-container">
                                <img src="/images/website-identity.png" alt="phone-identity" />
                            </div>
                            <div class="info-content">
                                <a href="{{$college->website}}" target="_blank">{{$college->website}}</a>

                            </div>
                        </div>
                    </div>

                    <ul class="ranks">
                        <li>
                            <div class="rank" style="background-color: #34a853">{{$college->qs_ranking}}</div>
                            <div class="rank-name">QS排名</div>
                        </li>
                        <li>
                            <div class="rank" style="background-color: #f9d339">{{$college->us_new_ranking}}</div>
                            <div class="rank-name">US New排名</div>
                        </li>
                        <li>
                            <div class="rank" style="background-color: #0e2459">{{$college->times_ranking}}</div>
                            <div class="rank-name">Times排名</div>
                        </li>
                        <li>
                            <div class="rank" style="background-color: #fb9c04">{{$college->domestic_ranking}}</div>
                            <div class="rank-name">国内排名</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="college-page-nav" id="colelge-page-nav">
            <div class="app-content">
                <ul>
                    <li @if($article_key == 'xue-xiao-gai-kuang')class="active"@endif>
                        <a href="{{ route('colleges.show', ['key' => $college->key]) }}#colelge-page-nav">学校概况</a>
                    </li>

                    <li @if($article_key == 'lu-qu-qing-kuang')class="active"@endif>
                        <a href="{{ route('colleges.show', ['key' => $college->key, 'article_type' => 'lu-qu-qing-kuang']) }}#colelge-page-nav">录取情况</a>
                    </li>

                    <li>
                        专业
                    </li>

                    <li>
                        图片
                    </li>
                    <li>
                        留学攻略
                    </li>
                </ul>
            </div>
        </div>

        <div class="college-page-detail">
            <div class="app-content">
                <div class="college-pages">
                    @include('colleges.show-'. $article_key, ['articles' => $articles])
                </div>
            </div>
        </div>
    </div>
@endsection