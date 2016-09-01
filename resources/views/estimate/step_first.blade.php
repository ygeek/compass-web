@extends('layouts.app')

@section('content')
    <div class="estimate-page">
        <div class="app-content">
            @include('shared.top_bar', ['page' => 'estimate'])
            <template id="union-select">

                <form class="estimate-form" action="{{route('estimate.step_second')}}">
                    <h1>选择留学意向·1/2</h1>

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
                                <template v-for="speciality in children">
                                    <option v-bind:value="speciality.name">@{{ speciality.name }}</option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <button class="estimate-button" v-on:click="onSubmit" style="margin-left: 210px;">下一步</button>
                </form>
            </template>
            <union-select></union-select>

        </div>
    </div>
    <script>
        Vue.component('union-select', {
            template: '#union-select',
            data: function () {
                return {
                    selected_category_id: {{$selected_category_id or 1}},
                    selected_speciality_name: '{{$selected_speciality_name or 'null'}}',
                    speciality_categories: {!! json_encode($speciality_categories) !!},
                    selected_country_id: {{$selected_country_id or 1}},
                    selected_degree_id: {{$selected_degree_id or 2}}
                }
            },
            computed: {
                children: function () {
                    var that = this;
                    for(var i=0; i<this.speciality_categories.length; i++){
                        if(this.speciality_categories[i].id == this.selected_category_id){
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
                    if (this.selected_speciality_name==""){
                        alert('专业库正在完善中，请选择其他专业。');
                        event.preventDefault();
                    }
                }
            }
        });
    </script>
@endsection
