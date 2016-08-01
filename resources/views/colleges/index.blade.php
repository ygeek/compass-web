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
                    <button type="submit" class="search-button"></button>
                </div>

                <div class="tag-area">
                    <div class="tag-select">
                        <tag-select label-name="选择国家" :selects="areas" :selected_id.sync="selected_country_id"></tag-select>
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
                </div>

                <div class="order-area">
                    <div class="result-tips">为您找到<span style="color: #38deba"> {{ $colleges->total() }} </span>相关院校</div>
                    <div class="order"></div>
                </div>
                </form>
            </template>
        </div>

        <div class="search-result">
            <div class="app-content">
                <div class="colleges">
                    @foreach($colleges as $college)
                        <div class="college">
                            <img class="college-badge" src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}" />
                            <div class="college-info">
                                <header>
                                    <h1><a href="{{route('colleges.show', $college->key)}}">{{$college->chinese_name}}</a></h1>
                                    <h2>{{$college->english_name}}</h2>

                                    <div class="ielts-and-toelf-requirement">
                                        <span class="toelf-requirement">托福: {{ $college->toeflRequirement('本科') }}</span>
                                        <span class="ielts-requirement">托福: {{ $college->ieltsRequirement('本科') }}</span>
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

                                    <a href="{{route('estimate.step_first')}}">测试录取几率-></a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $colleges->render() }}
                @include('colleges.recommend')
            </div>
        </div>


    <template id="tag-select">
        <div>
            <label>@{{ labelName }}</label>
            <div class="tags">
                <div class="tag"  v-bind:class="{'active': selected_id === null || selected_id === '' || selected_id === 0 }">
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
                    this.selected_id = item_id
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
                }
            }
        });
    </script>
    @include('shared.footer')
@endsection
