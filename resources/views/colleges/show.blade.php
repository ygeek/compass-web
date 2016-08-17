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
                        <h1>{{$college->chinese_name}}<span class="property">{{ ($college->type=="public")?'公立':'私立' }}</span></h1>
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

        <div class="college-page-nav" id="college-page-nav">
            <div class="app-content">
                <ul>
                    <img src="{{app('qiniu_uploader')->pathOfKey($college->icon_path)}}" alt="icon"/>
                    <span class="separator">●</span>
                    <a href="{{ route('colleges.show', ['key' => $college->key]) }}#college-page-nav">
                        <li @if($article_key == 'xue-xiao-gai-kuang')class="active"@endif>
                            学校概况
                        </li>
                    </a>
                    <a href="{{ route('colleges.show', ['key' => $college->key, 'article_type' => 'lu-qu-qing-kuang']) }}#college-page-nav">
                        <li @if($article_key == 'lu-qu-qing-kuang')class="active"@endif>
                            录取情况
                        </li>
                    </a>

                    <a href="{{ route('colleges.show', ['key' => $college->key, 'article_type' => 'specialities']) }}#college-page-nav">
                        <li @if($article_key == 'specialities')class="active"@endif>
                            专业
                        </li>
                    </a>

                    <a href="{{ route('colleges.show', ['key' => $college->key, 'article_type' => 'tu-pian']) }}#college-page-nav">
                        <li @if($article_key == 'tu-pian')class="active"@endif>
                            图片
                        </li>
                    </a>
                    <a href="{{ route('colleges.show', ['key' => $college->key, 'article_type' => 'liu-xue-gong-lue', 'desc' => '1']) }}#college-page-nav">
                        <li @if($article_key == 'liu-xue-gong-lue')class="active"@endif>
                            留学攻略
                        </li>
                    </a>
                </ul>

                <div class="actions">
                <template id="like-college">
                    <button v-if="liked == 0" class="estimate-button" @click="likeCollege"><span class="gray-heart"></span> @{{like_nums}}</button>

                    <button v-if="liked == 1" class="estimate-button" @click="dislikeCollege"><span class="heart"></span> @{{like_nums}}</button>
                </template>

                <like-college
                    college_id="{{ $college->id }}"
                    liked="<?php if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college->id)){echo 1;} else {echo 0;}}else{echo 0;} ?>"
                    like_nums="{{ $college->like_nums }}"
                ></like-college>

                    @include('shared.like_college', ['template_name' => 'like-college'])

                <a href="{{ route('colleges.rank') }}"><button class="estimate-button">院校排名 -></button></a>
                </div>
            </div>
        </div>
        @if($article_key == 'specialities')
        <div class="college-specialities-search">
            <div class="app-content">
                <search-form></search-form>
                <template id="search-form">
                <form>
                    <input type="hidden" name="article_type" value="specialities" />
                    <div class="search-area">
                        <input type="text" placeholder="输入要查询的专业" name="speciality_name" value="{{ app('request')->input('speciality_name') }}"/>
                        <button type="submit" class="search-button"></button>
                    </div>

                    <div class="tag-area">
                        <div class="tag-select">
                            <tag-select label-name="学位类型" :selects="degrees" :selected_id.sync="selected_degree_id"></tag-select>
                            <input number type="hidden" v-model="selected_degree_id" name="selected_degree_id" value="{{ app('request')->input('selected_degree_id') }}"/>
                        </div>

                        <div class="tag-select">
                            <tag-select label-name="选择专业方向" :selects="categories" :selected_id.sync="selected_category_id"></tag-select>
                            <input number type="hidden" v-model="selected_category_id" name="selected_category_id" value="{{ app('request')->input('selected_category_id') }}"/>
                        </div>

                        <div class="order-area">
                            <div class="result-tips">为您找到<span style="color: #38deba;font-weight: bold"> {{ count($articles) }} </span>个相关专业</div>
                            <div class="order"></div>
                        </div>
                    </div>
                    </form>
                </template>
            </div>
        </div>

        <template id="tag-select">
            <div>
                <label>@{{ labelName }}</label>
                <div class="tags">
                    <div class="tag"  v-bind:class="{'active': selected_id === null || selected_id === 0 }">
                        <span v-on:click="select_item(null)">不限</span>
                    </div>
                    <div v-for="item in selects" class="tag" v-on:click="select_item(item.id)" v-bind:class="{'active': selected_id == item.id}">
                        <span>@{{ item.name }}</span>
                    </div>
                </div>
            </div>
        </template>

        <script>
            Vue.component("tag-select", {
                template: '#tag-select',
                props: ['labelName', 'selects', 'selected_id'],
                methods: {
                    select_item: function (item_id) {
                        this.selected_id = item_id
                        this.$dispatch('call_submit');
                    }
                }
            });

            Vue.component("search-form", {
                template: '#search-form',
                data: function () {
                    return {
                        categories: <?php
    echo json_encode(
            collect(App\SpecialityCategory::all()->toArray())->map(function ($category){
                $new_category = $category;
                $new_category['name'] = $category['chinese_name'];
                return $new_category;
            })->toArray());
                    ?>,
                        degrees: {!!json_encode($college->degrees) !!},
                        selected_degree_id: null,
                        selected_category_id: null
                    }
                },
                events: {
                    'call_submit': function () {
                        setTimeout(function(){
                            document.getElementsByTagName('form')[0].submit()
                        }, 1);
                    }
                }
            });
        </script>
        @endif
        <div class="college-page-detail">
            <div class="app-content">
                <div class="college-pages">
                  <div class="page-content">
                    @include('colleges.show-'. $article_key, ['articles' => $articles])
                  </div>

                  <div class="hot-content">
                      @include('shared.advertisements', ['tag' => 'page_colleges_show'])
                      @include('colleges.sidebar', ['college' => $college])
                  </div>
                </div>
            </div>
        </div>
    </div>

    @include('shared.footer')
@endsection
