@extends('layouts.app')

@section('content')
<div class="home-page intentions">
    <div class="app-content">
        @include('shared.top_bar')

        <div class="page-content">
            @include('home.slider', ['active' => 'intention'])
            <div class="home-content">
                <intentions
                  :intentions='{!! json_encode($intentions) !!}'
                  :categories='{!! json_encode($speciality_categories) !!}'
                  :intention-colleges='{!! json_encode($intention_colleges) !!}'>
                </intentions>
                <template id="intentions">

                  <div class="mask" v-if="show_pop">
                    <div class="add-speciality-pop">
                        <div class="close" @click="show_pop=false">×</div>
                        <div class="form">
                          <div class="form-group">
                              <label>专业方向</label>
                              <select v-model="selected_category_id" style="width: 250px;">
                                  <template  v-for="category in show_data.categories">
                                      <option v-bind:value="category.id" v-if="category == show_data.categories[0]" selected>
                                          @{{ category.chinese_name }}
                                      </option>
                                      <option v-bind:value="category.id" v-else>
                                          @{{ category.chinese_name }}
                                      </option>
                                  </template>
                              </select>
                          </div>
                          <div class="form-group">
                              <label>专业</label>
                              <select v-model="selected_speciality_name" style="width: 250px;">
                                  <template v-for="speciality in select_specialities">
                                      <option v-bind:value="speciality.name" v-if="speciality == select_specialities[0]" selected>
                                          @{{ speciality.name }}
                                      </option>
                                      <option v-bind:value="speciality.name" v-else>
                                          @{{ speciality.name }}
                                      </option>
                                  </template>
                              </select>
                          </div>
                        </div>
                      </div>
                  </div>

                <div class="title">我的意向单 <button class="estimate-button" @click="commit">提交审核 @{{ selected_specialities_count }}/@{{ specialities_count }}</button></div>

                <div class="content">
                    <div class="intention" v-for="intentionCollege in intentionColleges">
                        <div class="college">
                            <button class="estimate-button" @click="addSpeciality(intention)">添加专业</button>

                            <img class="college-badge" v-bind:src="intentionCollege.badge_path" />
                            <div class="college-info">
                                <header>
                                    <a v-bind:href="intention.redirect_url" target="_blank">
                                      <h1>@{{intentionCollege.chinese_name}}<span class="property">@{{ (intentionCollege.type=="public")?'公立':'私立' }}</span></h1>
                                    </a>

                                    <h2>@{{intentionCollege.english_name}}</h2>

                                    <div class="ielts-and-toelf-requirement">
                                   <span class="toelf-requirement">托福: @{{ intentionCollege.toefl_requirement }}</span>
                                    <span class="ielts-requirement" style="margin-left: 35px">雅思: @{{ intentionCollege.ielts_requirement }}</span>
                                        <like-college
                                                :college_id="intentionCollege.id"
                                                :liked="intentionCollege.liked"
                                                :like_nums="intentionCollege.like_nums"
                                        ></like-college>
                                    </div>

                                    <div class="address-container">
                                        <div class="location">
                                            <img src="/images/location-identity.png" alt="location-identity" />
                                            @{{intentionCollege.area}}
                                        </div>

                                        <div class="address">
                                            @{{intentionCollege.address}}
                                        </div>
                                    </div>
                                </header>

                                <div class="college-rank-info">
                                    <table>
                                        <tr>
                                            <td>U.S.New排名:</td>
                                            <td>@{{intentionCollege.us_new_ranking}}</td>
                                        </tr>
                                        <tr>
                                            <td>Times排名:</td>
                                            <td>@{{intentionCollege.times_ranking}}</td>
                                        </tr>
                                        <tr>
                                            <td>QS排名:</td>
                                            <td>@{{intentionCollege.qs_ranking}}</td>
                                        </tr>
                                        <tr>
                                            <td>本国排名:</td>
                                            <td>@{{intentionCollege.domestic_ranking}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="college-intentions">
                          <table>
                              <tr v-for="intention in intentions[intentionCollege.id]">
                                <td>
                                  <input type="checkbox" v-model="intention.checked" />
                                </td>

                                <td style="text-align: left;">
                                  @{{ intention.speciality_name }}
                                </td>

                                <td>
                                  @{{ intention.degree.name }}
                                </td>

                                <td @click="displayIntentionDetail(intention)">
                                  查看详情
                                </td>

                                <td style="padding-right: 10px; cursor: pointer; color:#6d6d6d" @click="deleteSpeciality(intention)">x</td>

                              </tr>
                          </table>


                          <div id="estimate-detail-pop" class="mask" v-if="showIntentionDetail">
                              <div class="estimate-detail">
                                  <p class="match-title">匹配结果</p>
                                  <h1>@{{ showIntentionDetail.college.chinese_name }}</h1>
                                  <span @click="showIntentionDetail=false" class="close">×</span>

                                  <p class="title">您的录取率为<span style="color: red;font-size: 18px">@{{ showIntentionDetail.score }}%</span>，@{{ selected_speciality_name }}专业匹配如下：</p>
                                  <table>
                                      <tr>
                                          <th style="padding-left: 30px;">
                                              专业
                                          </th>
                                          <th v-for="contrast in showIntentionDetail.requirement_contrast" v-if="contrast['name']  != '备注'">
                                              @{{ contrast['name'] }}
                                          </th>
                                      </tr>
                                      <tr style="background: #fff">
                                          <td style="text-align: left;padding-left: 30px;">
                                              您的成绩
                                          </td>
                                          <td v-for="contrast in showIntentionDetail.requirement_contrast" v-if="contrast['name']  != '备注'">
                                              @{{ contrast['user_score'] }}
                                          </td>
                                      </tr>

                                      <tr>
                                          <td style="text-align: left;padding-left: 30px;">
                                              @{{ selected_speciality_name }}专业要求
                                          </td>
                                          <td v-for="contrast in showIntentionDetail.requirement_contrast" v-if="contrast['name']  != '备注'">
                                              @{{ contrast['require'] }}
                                          </td>
                                      </tr>

                                      <tr class="comment">
                                          <td
                                            style="text-align: left;line-height: 26px;padding-left: 30px;background: #fff;"
                                            v-bind:colspan="showIntentionDetail.requirement_contrast.length"
                                            v-for="contrast in showIntentionDetail.requirement_contrast"
                                            v-if="contrast['name']  == '备注'"
                                          >
                                            <div style="height: 115px;overflow: hidden">
                                                @{{{ changeLine(contrast['require']) }}}
                                            </div>
                                          </td>
                                      </tr>

                                  </table>
                              </div>
                          </div>

                        </div>
                    </div>
                </div>
                </template>

                <template id="like-college">
                    <span
                      v-if="liked == 0"
                      class="right"
                      style="margin-left: 20px;cursor: pointer;"
                      @click="likeCollege">
                        <span class="gray-heart"></span>
                        @{{like_nums}}
                    </span>
                    <span
                      v-if="liked == 1"
                      class="right"
                      style="margin-left: 20px;cursor: pointer;"
                      @click="dislikeCollege">
                        <span class="heart"></span>@{{like_nums}}
                    </span>
                </template>
                @include('shared.like_college', ['template_name' => 'like-college'])
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    Array.prototype.unique = function() {
        var a = [];
        for (var i=0, l=this.length; i<l; i++)
            if (a.indexOf(this[i]) === -1)
                a.push(this[i]);
        return a;
    }

    Array.prototype.contains = function(obj) {
        var i = this.length;
        while (i--) {
            if (this[i] === obj) {
                return true;
            }
        }
        return false;
    }
    Vue.component("intentions", {
        template: "#intentions",
        props: ['intentions', 'categories', 'intentionColleges'],
        data: function(){
            return {
                show_pop: false,
                showIntentionDetail: null,
                show_data: {
                    categories: [],
                    specialities: [],
                    intention: null
                },
                selected_category_id: null,
                selected_speciality_name: null
            }
        },
        computed: {
            select_specialities: function(){
                var self = this;

                var data = this.show_data.specialities.filter(function(item){
                    return item.category_id == self.selected_category_id;
                }).sort(function (a, b) {
                    return a.name.localeCompare(b.name);
                });

                return data;
            },
            specialities_count: function(){
                var nums = 0;
                var that = this;
                this.intentions.intentions.forEach(function(college){
                    nums += college.specialities.length;
                });
                return nums;
            },
            selected_specialities: function(){
                var res = [];
                this.intentions.intentions.forEach(function(college){
                    var keys = Object(college.specialities);
                    for(var key in keys){
                        if(college.specialities[key].checked){
                            res.push(college.specialities[key])
                        }
                    }
                });
                return res;
            },
            selected_specialities_count: function(){
                return this.selected_specialities.length;
            }
        },
        methods: {
            displayIntentionDetail: function(intentionDetail) {
              this.showIntentionDetail = intentionDetail;
            },
            addSpeciality: function(intention){
                this.show_data.intention = intention;
                var specialities = intention['college']['specialities'];
                var categorie_ids = specialities.map(function(item){
                    return item.category_id;
                }).unique();

                var categories = this.categories.filter(function(item){
                    return categorie_ids.contains(item.id);
                });

                this.show_data.categories = categories;
                this.show_data.specialities = specialities;
                this.show_pop = true;
            },
            postSpeciality: function(){
                var college_id = this.show_data.intention.college.id;
                var degree_id = this.intentions.degree_id;
                var estimate_id = this.intentions.estimate_id;
                var speciality_name = this.selected_speciality_name;

                if(this.selected_speciality_name==null){
                    this.selected_speciality_name = this.select_specialities[0].name;
                    speciality_name = this.selected_speciality_name;
                }

                for(var i in this.show_data.intention.specialities){
                    if(this.show_data.intention.specialities[i].speciality_name==speciality_name){
                        alert("该专业已存在");
                        return;
                    }
                }

                this.$http.post("{{ route('intentions.store') }}", {
                    college_id: college_id,
                    degree_id: degree_id,
                    estimate_id: estimate_id,
                    speciality_name: speciality_name
                }).then(function(response){
                    alert('加入意向单成功');
                    window.location.reload();
                }, function(response){
                    if(response.status == 401){
                        alert('请先登录')
                    };
                })
            },
            deleteSpeciality: function(speciality){
                var id = speciality._id;
                if(!confirm('确定删除专业？(若当前专业为院校最后一个专业、院校也会被删除)')){
                    return false;
                }
                var url = "{{ route('intentions.destroy', ['id' => 'id']) }}".replace('id', id);
                this.$http.delete(url).then(function(response){
                    alert('删除成功');
                    window.location.reload();
                });
            },
            commit: function(){
                //提交审核
                var estimate_id = this.intentions.estimate_id;
                var selected_speciality_ids = this.selected_specialities.map(function(speciality){
                    return speciality._id;
                });

                if(selected_speciality_ids.length == 0){
                    alert('未选择审核专业');
                    return;
                }

                var url = "{{ route('intentions.create') }}";
                this.$http.post(url, {
                    estimate_id: estimate_id,
                    selected_speciality_ids: selected_speciality_ids
                }, function(response){
                    alert('提交审核成功');
                });
            },
            countArray: function count(o){
                var t = typeof o;
                if(t == 'string'){
                    return o.length;
                }else if(t == 'object'){
                    var n = 0;
                    for(var i in o){
                        n++;
                    }
                    return n;
                }
                return false;
            }
        }
    });
</script>

    @include('shared.footer')
@endsection
