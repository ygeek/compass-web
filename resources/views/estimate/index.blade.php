@extends('layouts.app')

@section('content')
  @if(!$college_id)
    <div class="estimate-page estimate-result">
        <div class="app-content">
            @include('shared.top_bar', ['page' => 'estimate'])
  @else
    <div class="estimate-page estimate-result">
  @endif
            <estimate-result-list></estimate-result-list>
            <template id="estimate-result-list">
                <div id="estimate-detail-pop" class="mask" v-if="showRequirementContrasts">
                    <div class="estimate-detail">
                        <p class="match-title">匹配结果</p>
                        <h1>@{{ showRequirementContrastsContent.college.chinese_name }}</h1>
                        @if(!(isset($cpm) && $cpm))
                            <span @click="showRequirementContrasts=false" class="close">×</span>
                        @endif
                        <p class="title">您的录取率为<span style="color: red;font-size: 18px">@{{ showRequirementContrastsContent.score }}%</span>，@{{ selected_speciality_name }}专业匹配如下：</p>
                        <table>
                            <tr>
                                <th style="padding-left: 30px;">
                                    专业
                                </th>
                                <th v-for="contrast in showRequirementContrastsContent.contrasts" v-if="contrast['name']  != '备注'">
                                    @{{ contrast['name'] }}
                                </th>
                            </tr>
                            <tr style="background: #fff">
                                <td style="text-align: left;padding-left: 30px;">
                                    您的成绩
                                </td>
                                <td v-for="contrast in showRequirementContrastsContent.contrasts" v-if="contrast['name']  != '备注'">
                                    @{{ contrast['user_score'] }}
                                </td>
                            </tr>

                            @if(Auth::check())
                            <tr>
                                <td style="text-align: left;padding-left: 30px;">
                                    @{{ selected_speciality_name }}专业要求
                                </td>
                                <td v-for="contrast in showRequirementContrastsContent.contrasts" v-if="contrast['name']  != '备注'">
                                    @{{ contrast['require'] }}
                                </td>
                            </tr>
                                <tr class="comment">
                                    <td style="text-align: left;line-height: 26px;padding-left: 30px;background: #fff;" v-bind:colspan="showRequirementContrastsContent.contrasts.length" v-for="contrast in showRequirementContrastsContent.contrasts" v-if="contrast['name']  == '备注'">
                                        <div style="height: 115px;overflow: hidden">
                                            @{{{ changeLine(contrast['require']) }}}
                                        </div>
                                    </td>
                                </tr>
                             @else
                                <tr style="background: #f3f3f3;height: 160px">
                                    <td v-bind:colspan="showRequirementContrastsContent.contrasts.length">您好，请&nbsp;<a href="javascript:void(0)" v-on:click="callLogin">登录</a>&nbsp;以查看更多内容</td>
                                </tr>
                            @endif

                        </table>
                        <button class="estimate-button" @click="addIntention">加入意向单</button>
                    </div>
                </div>

                <div class="estimate-result-list">

                    @if(!$college_id)
                    <div class="identity">
                        <h1>匹配结果</h1>

                        <ul>
                            <li @click="show='sprint'" v-bind:class="{'active': show == 'sprint'}">
                            冲刺院校({{count($reduce_colleges['sprint'])}})
                            </li>
                            <li @click="show='core'" v-bind:class="{'active': show == 'core'}">
                            核心院校({{count($reduce_colleges['core'])}})
                            </li>
                        </ul>
                    </div>

                    <div class="college-list">
                        @foreach($reduce_colleges as $reduce_key => $colleges)
                            <div class="reduce-{{$reduce_key}}" v-show="show=='{{$reduce_key}}'">
                                @foreach($colleges as $college)
                                    <div class="college-show">
                                        <img class="college-badge" src="{{app('qiniu_uploader')->pathOfKey($college['college']['badge_path'])}}" />
                                        <div class="college-info">
                                            <header>
                                                <h1>{{$college['college']['chinese_name']}}<span class="property">{{ ($college['college']['type']=="public")?'公立':'私立' }}</span></h1>
                                                <h2>{{$college['college']['english_name']}}</h2>

                                                <div class="ielts-and-toelf-requirement">
                                                    <span class="toelf-requirement">托福: {{ $college['toefl_requirement'] }}</span>
                                                    <span class="ielts-requirement" style="margin-left: 35px">雅思: {{ $college['ielts_requirement'] }}</span>
                                                    <like-college
                                                            college_id="{{ $college['college']['id'] }}"
                                                            liked="<?php if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college['college']['id'])){echo 1;} else {echo 0;}}else{echo 0;} ?>"
                                                            like_nums="{{ $college['college']['like_nums'] }}"
                                                    ></like-college>
                                                </div>

                                                <div class="address-container">
                                                    <div class="location">
                                                        <img src="/images/location-identity.png" alt="location-identity" />
                                                        <?php
                                                        $area = App\AdministrativeArea::where('id',$college['college']['administrative_area_id'])->get();
                                                        echo ($area[0]->name);
                                                        while ($area[0]->parent_id!=null){
                                                            $area = App\AdministrativeArea::where('id',$area[0]->parent_id)->get();
                                                            echo (" , " . $area[0]->name);
                                                        }
                                                    ?>
                                                    </div>

                                                    <div class="address">
                                                        {{ $college['college']['address'] }}
                                                    </div>
                                                </div>
                                            </header>
                                            <div class="college-rank-info">
                                                <table>
                                                    <tr>
                                                        <td>U.S.New排名:</td>
                                                        <td>{{$college['college']['us_new_ranking']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Times排名:</td>
                                                        <td>{{$college['college']['times_ranking']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>QS排名:</td>
                                                        <td>{{$college['college']['qs_ranking']}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>本国排名:</td>
                                                        <td>{{$college['college']['domestic_ranking']}}</td>
                                                    </tr>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="score">
                                            <div class="content">
                                                <p>{{$college['score']}}<span>%</span></p>
                                                <p>匹配概率</p>
                                            </div>
                                        </div>

                                        <div class="view-matching-detail">
                                            <button data-college='{!! json_encode($college['college']) !!}' data-requirement-contrasts='{!! json_encode($college['requirement_contrast']) !!}' data-score="{{$college['score']}}" v-on:click="showDetail($event)" class="view-details-button">查看匹配详情-></button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    @else

                    @endif
                </div>

            </template>
            <template id="like-college">
                <span v-if="liked == 0" class="right" style="margin-left: 20px;cursor: pointer;" @click="likeCollege"><span class="gray-heart"></span>@{{like_nums}}</span>
                <span v-if="liked == 1" class="right" style="margin-left: 20px;cursor: pointer;" @click="dislikeCollege"><span class="heart"></span>@{{like_nums}}</span>
            </template>
            @include('shared.like_college', ['template_name' => 'like-college'])
        </div>
    </div>

    <script>
        Vue.component('estimate-result-list', {
            template: '#estimate-result-list',
            data: function () {
                return {
                    show: 'sprint',
                    selected_speciality_name: "{{ $data['selected_speciality_name'] }}",
                    estimate_id: "{{ $estimate_id }}",
                    selected_degree_id: {{ $selected_degree->id }},
                    @if(!$college_id)
                    showRequirementContrastsContent: {
                        contrasts: [],
                        college: {college_name: null},
                    },
                    @else
                    showRequirementContrastsContent: {
                        contrasts: {!! json_encode($res['requirement_contrast']) !!},
                        college: {!! json_encode($res['college']) !!},
                        score: {!! $res['score'] !!}
                    },
                    @endif
                    showRequirementContrasts: @if(!$college_id) false @else true @endif
                }
            },

            @if($college_id)
            created: function () {
                parent.window.document.getElementById("estimate_iframe").width='870px';
                parent.window.document.getElementById("estimate_iframe").height='400px';
                parent.window.document.getElementById("position_div").style.top='calc(50% - 200px)';
                parent.window.document.getElementById("position_div").style.right='calc(50% - 435px)';
            },
            @endif

            methods: {
                showDetail: function (contrasts) {
                    this.showRequirementContrastsContent.contrasts =
                        JSON.parse(contrasts.target.getAttribute('data-requirement-contrasts'));
                    this.showRequirementContrastsContent.college =
                        JSON.parse(contrasts.target.getAttribute('data-college'));
                    this.showRequirementContrasts = true;
                    this.showRequirementContrastsContent.score =
                            JSON.parse(contrasts.target.getAttribute('data-score'));
                },
                addIntention: function(){
                    var college_id = this.showRequirementContrastsContent.college.id;
                    var degree_id = this.selected_degree_id;
                    var estimate_id = this.estimate_id;
                    var speciality_name = this.selected_speciality_name;

                    this.$http.post("{{ route('intentions.store') }}", {
                        college_id: college_id,
                        degree_id: degree_id,
                        estimate_id: estimate_id,
                        speciality_name: speciality_name
                    }).then(function(response){
                        alert('加入意向单成功');
                        @if(!(isset($cpm) && $cpm))
                            window.location = "{{ route('home.intentions') }}";
                        @else
                            parent.window.document.getElementById("close_iframe").click();
                        @endif
                    }, function(response){
                        if(response.status == 401){
                            alert('请先登录')
                        };
                    })
                },
                changeLine: function (old) {
                    return old.replace(/\n/ig, "<br/>");
                },
                callLogin: function () {
                    this.$dispatch('toShowLoginAndRegisterPanel');
                }
            }
        });
    </script>
@endsection
