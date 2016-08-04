@extends('layouts.app')

@section('content')
<div class="home-page intentions">
    <div class="app-content">
        @include('shared.top_bar')

        <div class="page-content">
            @include('home.slider', ['active' => 'intention'])
            <div class="home-content">
                <intentions :intentions='{!! json_encode($intentions) !!}' :categories='{!! json_encode($speciality_categories) !!}'></intentions>
                <template id="intentions">
                <div class="mask" v-if="show_pop">
                    <div class="add-speciality-pop">
                        <div class="close" @click="show_pop=false">x</div>
                        <div>
                        <div class="form">
                        <div class="form-group">
                            <label>专业方向</label>
                            <select v-model="selected_category_id" style="width: 250px;">
                                <option v-bind:value="category.id" v-for="category in show_data.categories">
                                    @{{ category.chinese_name }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>专业</label>
                            <select v-model="selected_speciality_name" style="width: 250px;">
                                <option v-bind:value="speciality.name"v-for="speciality in select_specialities">
                                     @{{ speciality.name }}
                                </option>
                            </select>
                        </div>
                        <button class="estimate-button" @click="postSpeciality">添加专业</button>
                        </div>
                    </div>
                </div>
                </div>
                <div class="title">我的意向单 <button class="estimate-button" @click="commit">提交审核 @{{ selected_specialities_count }}/@{{ specialities_count }}</button></div>
                <div class="content">
                    <div class="intention" v-for="intention in intentions.intentions">
                        <div class="college">
                            <button class="estimate-button" @click="addSpeciality(intention)">添加专业</button>

                            <img class="college-badge" v-bind:src="intention.badge_path" />
                            <div class="college-info">
                                <header>
                                    <a v-bind:href="intention.redirect_url" target="_blank">
                                      <h1>@{{intention['college'].chinese_name}}</h1>
                                    </a>

                                    <h2>@{{intention['college'].english_name}}</h2>

                                    <div class="ielts-and-toelf-requirement">
                                   <span class="toelf-requirement">托福: @{{ intention['college'].toefl_requirement }}</span>
                                    <span class="ielts-requirement">雅思: @{{ intention['college'].ielts_requirement }}</span>
                                    </div>
                                </header>

                                <div class="college-rank-info">
                                    <table>
                                        <tr>
                                            <td>U.S.New排名:</td>
                                            <td>@{{intention['college'].us_new_ranking}}</td>
                                        </tr>
                                        <tr>
                                            <td>Times排名:</td>
                                            <td>@{{intention['college'].times_ranking}}</td>
                                        </tr>
                                        <tr>
                                            <td>QS排名:</td>
                                            <td>@{{intention['college'].qs_ranking}}</td>
                                        </tr>
                                        <tr>
                                            <td>本国排名:</td>
                                            <td>@{{intention['college'].domestic_ranking}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="college-intentions">
                        <table>
                            <tr style="background: #fff;">
                                <th>
                                </th>
                                <th style="text-align: left;">
                                    专业
                                </th>
                                <th v-for="key in score_keys">
                                    @{{ key }}
                                </th>
                                <th></th>
                            </tr>

                            <tr>
                                <td></td>
                                <td style="text-align: left;">
                                    您的成绩
                                </td>
                                <td v-for="key in score_keys">
                                    @{{ intentions['user_scores'][key] }}
                                </td>
                                <td></td>
                            </tr>

                            <tr v-for="speciality in intention.specialities">
                                <td>
                                <input type="checkbox" v-model="speciality.checked"/>
                                </td>
                                <td style="text-align: left;">
                                    @{{ speciality.speciality_name }}
                                </td>
                                <td v-for="key in score_keys">
                                    @{{ speciality.require[key] }}
                                </td>
                                <td style="padding-right: 10px; cursor: pointer; color:#6d6d6d" @click="deleteSpeciality(speciality)">x</td>
                            </tr>
                        </table>
                        </div>
                    </div>
                </div>
                </template>
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
        props: ['intentions', 'categories'],
        data: function(){
            return {
                show_pop: false,
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
            score_keys: function(){
                var Order = ['雅思','托福','听','说','读','写','高中平均成绩','高考','ACT','ACT作文','SAT','SAT阅读','SAT写作','SAT数学','GRE','GMAT','语文','数学','写作','相关专业工作年限','备注信息'];
                var keys = Object.keys(this.intentions.user_scores).sort(function(a,b) {
                    if (a.indexOf("高考") == 0)
                        a = '高考';
                    if (b.indexOf("高考") == 0)
                        b = '高考';
                    return Order.indexOf(a) - Order.indexOf(b);
                });
                return keys.filter(function(value){
                    return value != '备注';
                });
            },
            select_specialities: function(){
                var self = this;

                var data = this.show_data.specialities.filter(function(item){
                    return item.category_id == self.selected_category_id;
                });

                return data;
            },
            specialities_count: function(){
                var nums = 0;
                this.intentions.intentions.forEach(function(college){
                    nums += college.specialities.length
                });
                return nums;
            },
            selected_specialities: function(){
                var res = [];
                this.intentions.intentions.forEach(function(college){
                    var keys = Object(college.specialities);
                    for(key in keys){
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
            }
        }
    });
</script>

    @include('shared.footer')
@endsection
