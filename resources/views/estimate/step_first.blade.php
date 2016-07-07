@extends('layouts.app')

@section('content')
    <div class="estimate-page">
        <div class="app-content">
            @include('shared.top_bar')
            <template id="union-select">

                <form class="estimate-form" action="{{route('estimate.step_second')}}">
                    <h1>选择留学意向·1/2</h1>

                    <div class="form-group">
                        <label for="country">选择目标国家</label>
                        <div class="estimate-select">
                            <select name="selected_country_id" id="country" v-model="selected_country_id">
                                @foreach($countries as $country)
                                    <option value="{{$country->id}}">{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>

                    <div class="form-group">
                        <label for="degree">将要攻读学历</label>
                        <div class="estimate-select">
                            <select name="selected_degree_id" id="degree" v-model="selected_degree_id">
                                @foreach($degrees as $degree)
                                    <option value="{{$degree->id}}">{{$degree->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="years">计划留学时间</label>
                        <div class="estimate-select">
                            <select name="selected_year" id="years">
                                @foreach($years as $year)
                                    <option value="{{$year}}">{{$year}}</option>
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
                            <select name="speciality_name">
                                <template v-for="speciality in children">
                                    <option v-bind:value="speciality.name">@{{ speciality.name }}</option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <button class="estimate-button">下一步</button>
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
                    speciality_categories: {!! json_encode($speciality_categories) !!},
                    selected_category_id: null,
                    selected_country_id: null,
                    selected_degree_id: null
                }
            },
            computed: {
                children: function () {
                    var that = this;
                    for(var i=0; i<this.speciality_categories.length; i++){
                        if(this.speciality_categories[i].id == this.selected_category_id){
                            $res = this.speciality_categories[i].specialities.filter(function (speciality) {
                                return speciality.degree_id == that.selected_degree_id && speciality.country_id == that.selected_country_id;
                            });
                            return $res;
                        }
                    }
                    return [];
                }
            }
        });
    </script>
@endsection
