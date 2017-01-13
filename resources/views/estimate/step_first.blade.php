@extends('layouts.app')

@section('content')

    @if(!(isset($cpm) && $cpm))
    <div class="estimate-page">
        <div class="app-content">
            @include('shared.top_bar', ['page' => 'estimate'])
    @else
    <div class="cpm-estimate-page">
    @endif
            <template id="union-select">

                <form class="estimate-form" action="{{route('estimate.step_second')}}">
                    <h1>选择留学意向·1/2</h1>

                    @if(!$college_id)
                    <div class="form-group">
                        <label for="country">选择目标国家</label>
                        <div class="estimate-select">
                            <select name="selected_country_id" id="country" v-model="selected_country_id" number>
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}" @if($country->id == $selected_country_id) selected @endif>{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>
                    @endif

                    <div class="form-group">
                        <label for="degree">将要攻读学历</label>
                        <div class="estimate-select">
                            <select name="selected_degree_id" id="degree" v-model="selected_degree_id">
                                @foreach($degrees as $degree)
                                    <option value="{{$degree->id}}" @if($degree->id == $selected_degree_id) selected @endif>{{$degree->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if(!$college_id)
                    <div class="form-group">
                        <label for="years">计划留学时间</label>
                        <div class="estimate-select">
                            <select name="selected_year" id="years">
                                @foreach($years as $year)
                                    <option value="{{$year}}" @if($year == $selected_year) selected @endif>{{$year}}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    @endif

                    <div class="form-group">
                        <label for="speciality_categories">期望就读专业</label>
                        <div class="estimate-select">
                            <select name="speciality_category_id" id="speciality_categories" v-model="selected_category_id">
                                <template v-for="speciality_category in speciality_categories">
                                    <option v-bind:value="speciality_category.id">@{{ speciality_category.chinese_name }}</option>
                                </template>
                            </select>
                        </div>

                        <div class="estimate-select">
                            <select name="speciality_name" v-model="selected_speciality_name">
                                <template v-for="speciality in orderdChildren">
                                  <option v-bind:value="speciality" v-if="speciality == selected_speciality_name" selected>@{{ speciality }}</option>
                                  <option v-bind:value="speciality" v-else>@{{ speciality }}</option>
                                </template>
                            </select>
                        </div>
                    </div>

                    @if(isset($cpm))
                    <input type="hidden" name="cpm" value="{{ $cpm }}">
                    @endif

                    @if($college_id)
                    <input type="hidden" name="college_id" value="{{ $college_id }}">
                    @endif

                    <button class="estimate-button" v-on:click="onSubmit" @if(!(isset($cpm) && $cpm)) style="margin-left: 210px;" @else style="margin-left: 140px;" @endif>下一步</button>
                </form>
            </template>
            <union-select></union-select>

    @if(!(isset($cpm) && $cpm))
        </div>
    </div>
    @else
    </div>
    @endif

    <script>


    function compareFunc(param1,param2){
         //如果两个参数均为字符串类型
         if(typeof param1 == "string" && typeof param2 == "string"){
             return param2.localeCompare(param1);
         }
         //如果参数1为数字，参数2为字符串
         if(typeof param1 == "number" && typeof param2 == "string"){
             return -1;
         }
         //如果参数1为字符串，参数2为数字
         if(typeof param1 == "string" && typeof param2 == "number"){
             return 1;
         }
         //如果两个参数均为数字
         if(typeof param1 == "number" && typeof param2 == "number"){
             if(param1 > param2) return 1;
             if(param1 == param2) return 0;
             if(param1 < param2) return -1;
         }
     }

        Vue.component('union-select', {
            template: '#union-select',
            data: function () {
                return {
                    selected_category_id: {{$selected_category_id or 1}},
                    selected_speciality_name: '{{$selected_speciality_name or ''}}',
                    speciality_categories: {!! json_encode($speciality_categories) !!},
                    selected_country_id: {{$selected_country_id or 1}},
                    selected_degree_id: {{$selected_degree_id or 2}}
                }
            },
            created: function(){
                @if(!$college_id)
                  var url = "{{route('estimate.get_speciality')}}";
                @else
                  var url = "{{route('estimate.get_speciality', ['college_id' => $college_id])}}"
                @endif

                this.$http.get(url).then(function(response){
                    this.speciality_categories = response.data;
                    var tmp = this.children;
                });
                    @if($college_id)
                    if(parent.window.document.getElementById("estimate_iframe")) {
                      parent.window.document.getElementById("estimate_iframe").width='500px';
                      parent.window.document.getElementById("estimate_iframe").height='500px';
                      parent.window.document.getElementById("position_div").style.top='calc(50% - 250px)';
                      parent.window.document.getElementById("position_div").style.right='calc(50% - 250px)';
                    }

                    @endif
            },
            computed: {
                orderdChildren: function() {
                  res = _.uniqBy(_.map(this.children, function(s) {
                    return _.trim(s.name);
                  })).sort(function(a,b){return a.localeCompare(b, 'zh')});
                  return res;
                },
                children: function () {
                    var that = this;
                    for(var i=0; i<this.speciality_categories.length; i++){
                        if(this.speciality_categories[i].id == this.selected_category_id){
                            if(this.speciality_categories[i].specialities==null){
                                if (this.selected_speciality_name == "") {
                                    this.selected_speciality_name = '专业加载中';
                                    return [{'name': '专业加载中'}];
                                }
                                else {
                                    return [{'name': this.selected_speciality_name}];
                                }
                            }
                            var tmp = this.speciality_categories[i].specialities.filter(function (speciality) {
                                return speciality.degree_id == that.selected_degree_id && speciality.country_id == that.selected_country_id;
                            });

                            var res = [], tmp_name = [];
                            for (var k=0, l=tmp.length; k<l; k++)
                                if (tmp_name.indexOf(tmp[k].name) === -1 && tmp[k] !== '') {
                                    tmp_name.push(tmp[k].name);
                                    res.push(tmp[k]);
                                }

                            for (var j=0 ;j<res.length;j++){
                                if (that.selected_speciality_name==res[j].name){
                                    that.selected_speciality_name = res[j].name;
                                    return res;
                                }
                            }
                            if (res.length==0)
                                that.selected_speciality_name = "";
                            else
                                that.selected_speciality_name = res[0].name;

                            return res;
                        }
                    }
                    return [];
                }
            },
            methods: {
                onSubmit: function (event) {
                    if (this.selected_speciality_name=="专业加载中"){
                        alert('专业加载中，请稍等。');
                        event.preventDefault();
                    }
                    if (this.selected_speciality_name==""){
                        alert('专业库正在完善中，请选择其他专业。');
                        event.preventDefault();
                    }
                }
            }
        });
    </script>
@endsection
