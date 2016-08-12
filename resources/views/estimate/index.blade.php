@extends('layouts.app')

@section('content')
    <div class="estimate-page estimate-result">
        <div class="app-content">
            @include('shared.top_bar', ['page' => 'estimate'])
            <estimate-result-list></estimate-result-list>
            <template id="estimate-result-list">
                <div id="estimate-detail-pop" class="mask" v-if="showRequirementContrasts">
                    <div class="estimate-detail">
                        <h1>@{{ showRequirementContrastsContent.college.chinese_name }}的@{{ selected_speciality_name }}专业匹配如下：</h1>
                        <span @click="showRequirementContrasts=false" class="close">×</span>
                        <table>
                            <tr>
                                <th>
                                    专业
                                </th>
                                <th v-for="contrast in showRequirementContrastsContent.contrasts" v-if="contrast['name']  != '备注'">
                                    @{{ contrast['name'] }}
                                </th>
                            </tr>
                            <tr>
                                <td style="text-align: left;">
                                    您的成绩
                                </td>
                                <td v-for="contrast in showRequirementContrastsContent.contrasts" v-if="contrast['name']  != '备注'">
                                    @{{ contrast['user_score'] }}
                                </td>
                            </tr>

                            <tr>
                                <td style="text-align: left;">
                                    @{{ selected_speciality_name }}专业要求
                                </td>
                                <td v-for="contrast in showRequirementContrastsContent.contrasts" v-if="contrast['name']  != '备注'">
                                    @{{ contrast['require'] }}
                                </td>
                            </tr>

                            <tr class="comment">
                                <td style="text-align: left;line-height: 26px" v-bind:colspan="showRequirementContrastsContent.contrasts.length"v-for="contrast in showRequirementContrastsContent.contrasts" v-if="contrast['name']  == '备注'">
                                    @{{{ changeLine(contrast['require']) }}}
                                </td>
                            </tr>

                        </table>
                        <button class="estimate-button" @click="addIntention">加入意向单</button>
                    </div>
                </div>

                <div class="estimate-result-list">

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
                                                <span class="property top-right">{{ ($college['college']['type']=="public")?'公立':'私立' }}</span>
                                                <h1>{{$college['college']['chinese_name']}}(<?php
                                                        $area = App\AdministrativeArea::where('id',$college['college']['administrative_area_id'])->get();
                                                        while ($area[0]->parent_id!=null){
                                                            $area = App\AdministrativeArea::where('id',$area[0]->parent_id)->get();
                                                        }
                                                        echo ($area[0]->name);
                                                    ?>)</h1>
                                                <h2>{{$college['college']['english_name']}}</h2>

                                                <div class="ielts-and-toelf-requirement">
                                                    <span class="toelf-requirement">托福: {{ $college['toefl_requirement'] }}</span>
                                                    <span class="ielts-requirement" style="margin-left: 20px">雅思: {{ $college['ielts_requirement'] }}</span>
                                                    <like-college
                                                            college_id="{{ $college['college']['id'] }}"
                                                            liked="<?php if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college['college']['id'])){echo 1;} else {echo 0;}}else{echo 0;} ?>"
                                                            like_nums="{{ $college['college']['like_nums'] }}"
                                                    ></like-college>
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
                                            <button data-college='{!! json_encode($college['college']) !!}' data-requirement-contrasts='{!! json_encode($college['requirement_contrast']) !!}' v-on:click="showDetail($event)" class="view-details-button">查看匹配详情-></button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </template>
            <template id="like-college">
                <span v-if="liked == 0" class="right" style="margin-left: 20px;cursor: pointer;" @click="likeCollege">收藏(@{{like_nums}})</span>
                <span v-if="liked == 1" class="right" style="margin-left: 20px;cursor: pointer;" @click="dislikeCollege">取消收藏(@{{like_nums}})</span>
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
                    showRequirementContrastsContent: {
                        contrasts: [],
                        college: {college_name: null},
                    },
                    showRequirementContrasts: false
                }
            },
            methods: {
                showDetail: function (contrasts) {
                    this.showRequirementContrastsContent.contrasts =
                        JSON.parse(contrasts.target.getAttribute('data-requirement-contrasts'));
                    this.showRequirementContrastsContent.college =
                        JSON.parse(contrasts.target.getAttribute('data-college'));
                    this.showRequirementContrasts = true
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
                        window.location = "{{ route('home.intentions') }}";
                    }, function(response){
                        if(response.status == 401){
                            alert('请先登录')
                        };
                    })
                },
                changeLine: function (old) {
                    return old.replace(/\n/ig, "<br/>");
                }
            }
        });
    </script>
@endsection
