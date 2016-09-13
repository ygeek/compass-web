@extends('layouts.app')

@section('content')
    <div class="colleges-page">
        <div class="app-content">
            @include('shared.top_bar', ['page' => 'colleges'])
            <search-form></search-form>
            <template id="search-form">
                <form>
                <div class="search-area">
                    <input type="text" placeholder="输入院校名称" name="college_name" value="{{$college_name}}"/>
                    <input type="hidden" name="selected_country_id" value="-1"/>
                    <button type="submit" class="search-button"></button>
                </div>
                </form>

                <form id="search_form">
                <div class="tag-area">
                    <div class="tag-select">
                        <tag-select label-name="选择国家" :selects="areas" :selected_id.sync="selected_country_id" ></tag-select>
                        <input type="hidden" number v-model="selected_country_id"  name="selected_country_id" value="{{$selected_country_id }}"/>
                    </div>

                    <div v-if="states.length > 0" class="tag-select">
                        <tag-select label-name="选择地区" :selects="states" :selected_id.sync="selected_state_id"></tag-select>
                        <input type="hidden" number v-model="selected_state_id"  name="selected_state_id" value="{{$selected_state_id}}"/>
                    </div>

                    <div v-if="cities.length > 0" class="tag-select">
                        <tag-select label-name="选择城市" :selects="cities" :selected_id.sync="selected_city_id"></tag-select>
                        <input type="hidden" number v-model="selected_city_id"  name="selected_city_id" value="{{$selected_city_id}}"/>
                    </div>

                    <div class="tag-select">
                        <tag-select label-name="选择专业" :selects="speciality_categories" :selected_id.sync="selected_speciality_cateogry_id"></tag-select>
                        <input type="hidden" v-model="selected_speciality_cateogry_id"  number name="selected_speciality_cateogry_id" value="{{$selected_speciality_cateogry_id}}"/>
                    </div>

                    <div class="tag-select" v-if="selected_country_id == 1">
                        <tag-select label-name="选择类型" :selects="go8_selects" :selected_id.sync="selected_go8"></tag-select>
                        <input type="hidden" v-model="selected_go8" name="selected_go8" value="{{$selected_go8}}" number/>
                    </div>

                    <div class="tag-select">
                        <tag-select label-name="选择性质" :selects="property_selects" :selected_id.sync="selected_property"></tag-select>
                        <input type="hidden" v-model="selected_property" name="selected_property" value="{{$selected_property}}" number/>
                    </div>

                    <div class="tag-select">
                        <label>国内排名</label>
                        <div class="tags">
                            <div class="tag"  v-bind:class="{'active': (rank_start == null && rank_end == null) || (rank_start == '' && rank_end == '')}">
                                <span v-on:click="set_rank(null,null)">不限</span>
                            </div>
                            <div class="tag"  v-bind:class="{'active': (rank_start == 1 && rank_end == 8)}" v-if="selected_country_id == 1">
                                <span v-on:click="set_rank(1,8)">1-8名</span>
                            </div>
                            <div class="tag"  v-bind:class="{'active': (rank_start == 9 && rank_end == 20)}" v-if="selected_country_id == 1">
                                <span v-on:click="set_rank(9,20)">9-20名</span>
                            </div>
                            <div class="tag"  v-bind:class="{'active': (rank_start == 21 && rank_end == 50)}" v-if="selected_country_id == 1">
                                <span v-on:click="set_rank(21,50)">21-50名</span>
                            </div>
                            <div class="tag"  v-bind:class="{'active': (rank_start == 1 && rank_end == 20)}" v-if="selected_country_id == 31">
                                <span v-on:click="set_rank(1,20)">1-20名</span>
                            </div>
                            <div class="tag"  v-bind:class="{'active': (rank_start == 21 && rank_end == 30)}" v-if="selected_country_id == 31">
                                <span v-on:click="set_rank(21,30)">21-30名</span>
                            </div>
                            <div class="tag"  v-bind:class="{'active': (rank_start == 31 && rank_end == 60)}" v-if="selected_country_id == 31">
                                <span v-on:click="set_rank(31,60)">31-60名</span>
                            </div>
                            <div class="tag"  v-bind:class="{'active': (rank_start == 1 && rank_end == 10)}" v-if="selected_country_id == 71 || selected_country_id == 146">
                                <span v-on:click="set_rank(1,10)">1-10名</span>
                            </div>
                            <div class="tag"  v-bind:class="{'active': (rank_start == 11 && rank_end == 20)}" v-if="selected_country_id == 71 || selected_country_id == 146">
                                <span v-on:click="set_rank(11,20)">11-20名</span>
                            </div>
                            <div class="tag"  v-bind:class="{'active': (rank_start == 21 && rank_end == 50)}" v-if="selected_country_id == 71 || selected_country_id == 146">
                                <span v-on:click="set_rank(21,50)">21-50名</span>
                            </div>
                            <div class="tag">
                                <input type="text" v-model="rank_start" class="search-input" name="rank_start" value="{{$rank_start}}"/>
                                <span style="cursor:auto">~</span>
                                <input type="text" v-model="rank_end" class="search-input" name="rank_end" value="{{$rank_end}}"/>
                                <button v-on:click="set_rank(-1,-1)">确定</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-area">
                    <div class="result-tips">为您找到<span style="color: #38deba;font-weight: bold"> {{ $colleges->total() }} </span>个相关院校</div>
                    <div class="tag-area" style="float: left;margin-top: 0">
                        <div class="tag-select" style="margin-bottom: 0">
                            <div class="tags" style="width: auto">
                                <div class="tag"  v-bind:class="{'active': (selected_order == 'us_new_ranking')}">
                                    <span v-on:click="set_order('us_new_ranking')">U.S.News排名</span>
                                    <img v-if="selected_order == 'us_new_ranking'" src="/images/up.jpg" style="margin-left:2px;width:15px;height: 15px;vertical-align:middle;"/>
                                </div>
                                <div class="tag"  v-bind:class="{'active': (selected_order == 'times_ranking')}">
                                    <span v-on:click="set_order('times_ranking')">Times排名</span>
                                    <img v-if="selected_order == 'times_ranking'" src="/images/up.jpg" style="margin-left:2px;width:15px;height: 15px;vertical-align:middle;"/>
                                </div>
                                <div class="tag"  v-bind:class="{'active': (selected_order == 'qs_ranking')}">
                                    <span v-on:click="set_order('qs_ranking')">QS排名</span>
                                    <img v-if="selected_order == 'qs_ranking'" src="/images/up.jpg" style="margin-left:2px;width:15px;height: 15px;vertical-align:middle;"/>
                                </div>
                                <div class="tag"  v-bind:class="{'active': (selected_order == 'domestic_ranking')}">
                                    <span v-on:click="set_order('domestic_ranking')">本国排名</span>
                                    <img v-if="selected_order == 'domestic_ranking'" src="/images/up.jpg" style="margin-left:2px;width:15px;height: 15px;vertical-align:middle;"/>
                                </div>
                                <div class="tag"  v-bind:class="{'active': (selected_order == 'read_count_order')}">
                                    <span v-on:click="set_order('read_count_order')">浏览热度</span>
                                    <img v-if="selected_order == 'read_count_order'" src="/images/up.jpg" style="margin-left:2px;width:15px;height: 15px;vertical-align:middle;"/>
                                </div>
                                <div class="tag"  v-bind:class="{'active': (selected_order == 'average_enrollment_order')}">
                                    <span v-on:click="set_order('average_enrollment_order')">平均录取率</span>
                                    <img v-if="selected_order == 'average_enrollment_order'" src="/images/up.jpg" style="margin-left:2px;width:15px;height: 15px;vertical-align:middle;"/>
                                </div>
                                <div class="tag"  v-bind:class="{'active': (selected_order == 'international_ratio_order')}">
                                    <span v-on:click="set_order('international_ratio_order')">国际学生比例</span>
                                    <img v-if="selected_order == 'international_ratio_order'" src="/images/up.jpg" style="margin-left:2px;width:15px;height: 15px;vertical-align:middle;"/>
                                </div>
                            </div>
                            <input type="hidden" v-model="selected_order"  name="selected_order" value="{{$selected_order }}"/>
                        </div>
                    </div>
                </div>
                </form>
            </template>
        </div>

        <div class="search-result">
            <div class="app-content">
                <div class="colleges">
                    @foreach($colleges as $college)
                        <div class="college">
                            <like-college-sidebar
                                    college_id="{{ $college->id }}"
                                    liked="<?php if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college->id)){echo 1;} else {echo 0;}}else{echo 0;} ?>"
                                    like_nums="{{ $college->like_nums }}"
                            ></like-college-sidebar>

                            <?php
                                $tmp = $college->administrativeArea->id;
                                if ($college->administrativeArea->parent){
                                    $tmp = $college->administrativeArea->parent->id;
                                    if ($college->administrativeArea->parent->parent){
                                        $tmp = $college->administrativeArea->parent->parent->id;
                                    }
                                }
                                $estimate_url = route('estimate.step_first', ['selected_country_id' => $tmp, 'cpm' => true, 'college_id' => $college->id]);
                            ?>
                            <a href="javascript:void(0)" class="calc-link" v-on:click="setEstimatePanel('{{$estimate_url}}')">测试录取几率-></a>
                            <a href="{{route('colleges.show', $college->key)}}" target="_blank"><div class="cover"></div></a>
                            <img class="college-badge" src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}" />
                            <div class="college-info">
                                <header>
                                    <h1><a href="{{route('colleges.show', $college->key)}}" target="_blank">{{$college->chinese_name}}</a><span class="property">{{ ($college->type=="public")?'公立':'私立' }}</span></h1>
                                    <h2>{{$college->english_name}}</h2>

                                    <div class="ielts-and-toelf-requirement">
                                        <span class="toelf-requirement">托福: {{ $college->toeflRequirement('本科') }}</span>
                                        <span class="ielts-requirement" style="margin-left: 35px">雅思: {{ $college->ieltsRequirement('本科') }}</span>
                                    </div>

                                    <div class="address-container">
                                        <div class="location">
                                            <img src="/images/location-identity.png" alt="location-identity" />
                                            {{$college->administrativeArea->name}}
                                            @if($college->administrativeArea->parent)
                                                , {{$college->administrativeArea->parent->name}}
                                                @if($college->administrativeArea->parent->parent)
                                                    , {{$college->administrativeArea->parent->parent->name}}
                                                @endif
                                            @endif
                                        </div>

                                        <div class="address">
                                            {{ $college->address }}
                                        </div>
                                    </div>
                                </header>

                                <div class="college-rank-info">
                                    <table>
                                        <tr>
                                            <td>U.S.New排名:</td>
                                            <td>{{$college->us_new_ranking}}</td>
                                        </tr>
                                        <tr>
                                            <td>Times排名:</td>
                                            <td>{{$college->times_ranking}}</td>
                                        </tr>
                                        <tr>
                                            <td>QS排名:</td>
                                            <td>{{$college->qs_ranking}}</td>
                                        </tr>
                                        <tr>
                                            <td>本国排名:</td>
                                            <td>{{$college->domestic_ranking}}</td>
                                        </tr>
                                    </table>
                                </div>

                            </div>
                        </div>
                    @endforeach
                        {{ $colleges->appends(app('request')->except('page'))->render() }}
                </div>

                <?php unset($college) ?>
                <div class="hot-content" style="margin-top: 25px">
                    @include('shared.advertisements', ['tag' => 'page_colleges_index'])
                </div>
                <div class="clearfix"></div>

                @include('colleges.recommend')
            </div>
        </div>

        <template id="like-college-sidebar">
            <span v-if="liked == 0" class="like-button" @click="likeCollege"><span class="gray-heart"></span>@{{like_nums}}</span>
            <span v-if="liked == 1" class="like-button" @click="dislikeCollege"><span class="heart"></span>@{{like_nums}}</span>
        </template>
        @include('shared.like_college', ['template_name' => 'like-college-sidebar'])

        @include('shared.estimate')

    <template id="tag-select">
        <div>
            <label>@{{ labelName }}</label>
            <div class="tags">
                <div class="tag"  v-bind:class="{'active': selected_id === null || selected_id === '' || selected_id === 0 }" v-show="labelName!='选择国家'">
                    <span v-on:click="select_item(null)">不限</span>
                </div>
                <div v-for="item in selects" class="tag" v-on:click="select_item(item.id)" v-bind:class="{'active': selected_id === item.id}">
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
                    this.selected_id = item_id;
                    this.$dispatch('call_submit');
                }
            }
        });

        Vue.component("search-form", {
            template: '#search-form',
            data: function () {
                return {
                    areas: {!! json_encode($areas->toArray()) !!},
                    selected_country_id: null,
                    selected_state_id: null,
                    selected_city_id: null,
                    selected_go8: null,
                    selected_speciality_cateogry_id: null,
                    selected_property: null,
                    rank_start: null,
                    rank_end: null,
                    selected_order: 'domestic_ranking',
                    go8_selects: [
                      {
                        id: 1,
                        name: '八大',
                      },
                      {
                        id: 2,
                        name: '非八大'
                      }
                    ],
                    property_selects: [
                        {
                            id: 1,
                            name: '公立',
                        },
                        {
                            id: 2,
                            name: '私立'
                        }
                    ],
                    speciality_categories: <?php
    echo json_encode(
            collect($speciality_categories->toArray())->map(function ($category){
                $new_category = $category;
                $new_category['name'] = $category['chinese_name'];
                return $new_category;
            })->toArray());
                    ?>
                }
            },
            computed: {
                is_australia: function(){
                  return this.selected_country_id == 1;
                },
                states: function () {
                    var that = this;
                    var res = [];
                    this.areas.forEach(function(area){
                        if(area.id == that.selected_country_id){
                            res =  area.children;
                        }
                    });
                    return res;
                },

                cities: function () {
                    var that = this;
                    var res = [];

                    this.states.forEach(function(area){
                        if(area.id == that.selected_state_id){
                            res = area.children;
                        }
                    });
                    return res;
                },
                estimate_url: function () {
                    var url = '{{route('estimate.step_first', ['selected_country_id' => 'tmp'])}}';
                    url = url.replace('tmp', selected_country_id);
                    return url;
                }
            },
            methods: {
                select_country: function (country_id) {
                    this.selected_country_id = country_id;
                    this.selected_state_id = null;
                    this.selected_city_id = null;
                },
                select_state: function (state_id) {
                    this.selected_state_id = state_id;
                    this.selected_city_id = null;
                },
                select_city: function (city_id) {
                    this.selected_city_id = city_id;
                },
                call_submit_method: function () {
                    setTimeout(function(){
                        document.getElementById('search_form').submit()
                    }, 1);
                },
                set_rank: function (start, end){
                    if (start == -1 && end == -1){
                        this.call_submit_method();
                        return;
                    }
                    this.rank_start = start;
                    this.rank_end = end;
                    this.call_submit_method();
                },
                set_order: function (order){
                    this.selected_order = order;
                    this.call_submit_method();
                }
            },
            events: {
                'call_submit': function () {
                    setTimeout(function(){
                        document.getElementById('search_form').submit()
                    }, 1);
                }
            },
            watch: {
                'selected_country_id': function (val, oldVal) {
                    if (oldVal!=null && val!=oldVal){
                        this.selected_state_id = null;
                        this.selected_city_id = null;
                        this.rank_start = null;
                        this.rank_end = null;
                    }
                },
                'selected_state_id': function (val, oldVal) {
                    if (oldVal!=null && val!=oldVal){
                        this.selected_city_id = null;
                    }
                }
            }
        });
    </script>
    @include('shared.footer')
@endsection
