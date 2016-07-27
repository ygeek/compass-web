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
                    <a href="{{ route('colleges.show', ['key' => $college->key]) }}">
                        <li @if($article_key == 'xue-xiao-gai-kuang')class="active"@endif>
                            学校概况
                        </li>
                    </a>
                    <a href="{{ route('colleges.show', ['key' => $college->key, 'article_type' => 'lu-qu-qing-kuang']) }}">
                        <li @if($article_key == 'lu-qu-qing-kuang')class="active"@endif>
                            录取情况
                        </li>
                    </a>

                    <a href="{{ route('colleges.show', ['key' => $college->key, 'article_type' => 'specialities']) }}">
                        <li @if($article_key == 'specialities')class="active"@endif>
                            专业
                        </li>
                    </a>

                    <a href="{{ route('colleges.show', ['key' => $college->key, 'article_type' => 'tu-pian']) }}">
                        <li @if($article_key == 'tu-pian')class="active"@endif>
                            图片
                        </li>
                    </a>
                    <a href="{{ route('colleges.show', ['key' => $college->key, 'article_type' => 'liu-xue-gong-lue', 'desc' => '1']) }}">
                        <li @if($article_key == 'liu-xue-gong-lue')class="active"@endif>
                            留学攻略
                        </li>
                    </a>
                </ul>

                <div class="actions">
                <template id="like-college">
                    <button v-if="liked == 0" class="estimate-button" @click="likeCollege">收藏 <span class="gray-heart"></span></button>

                    <button v-if="liked == 1" class="estimate-button" @click="dislikeCollege">取消收藏 <span class="heart"></span></button>
                </template>

                <like-college
                    college_id="{{ $college->id }}"
                    liked="<?php if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college->id)){echo 1;} else {echo 0;}}else{echo 0;} ?>"
                ></like-college>
                <script type="text/javascript">
                    Vue.component('like-college', {
                        template: '#like-college',
                        props: ['college_id', 'liked'],
                        methods: {
                            likeCollege: function(){
                                var that = this;
                                this.$http.post("{{ route('like.store') }}", {
                                    college_id: this.college_id
                                }).then(function(){
                                    alert('收藏成功');
                                    that.liked = true;

                                }, function(response){
                                    if(response.status == 401){
                                        alert('请登陆后收藏院校');
                                    };
                                });
                            },
                            dislikeCollege: function(){
                                var that = this;
                                this.$http.post("{{ route('like.destroy') }}", {
                                    college_id: this.college_id
                                }).then(function(){
                                    alert('取消收藏成功');
                                    that.liked = false;

                                }, function(response){

                                });
                            }
                        }
                    });
                </script>
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
                    @include('colleges.sidebar')
                  </div>
                </div>
            </div>
        </div>
    </div>

    @include('shared.footer')
@endsection
