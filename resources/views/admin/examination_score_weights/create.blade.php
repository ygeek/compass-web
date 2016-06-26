@extends('layouts.admin')
@section('content')
    <rule-form></rule-form>
    <template id="rule-form">
        <div class="block">
            <div class="block-header">
                <ul class="block-options">
                    <li>
                        <button type="button"><i class="si si-settings"></i></button>
                    </li>
                </ul>
                <h3 class="block-title">新增规则</h3>
            </div>
            <div class="block-content block-content-narrow">
                {!! Form::open(['route' => 'admin.examination_score_weights.store', 'class'=> 'form-horizontal']) !!}
                <div class="form-group">
                    <label class="col-xs-12" for="name">规则名称</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" id="name" name="name" placeholder="规则名称">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-12" for="country">选择国家</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="country" name="country_id" v-model="country_id">
                            @foreach($countries as $country)
                                <option value="{{$country->id}}">{{$country->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-12" for="degree">选择学历</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="degree" name="degree_id" v-model="degree_id">
                            @foreach($degrees as $degree)
                                <option value="{{$degree->id}}">{{$degree->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <input type="hidden" name="weights" value="@{{examination_map | json}}" />
                    <label class="col-xs-12">比例设置</label>
                    <div class="col-sm-9">
                        <table class="table">
                            <tr v-for="examination in examination_map">
                                <td>
                                    @{{ examination.visible }}
                                </td>
                                <td>
                                    <input v-model="examination.weight" class="form-control" type="number" placeholder="百分比" min="0" max="100"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <button type="submit">提交</button>
                {!! Form::close() !!}
            </div>
        </div>
    </template>
    <script>
        var vm = Vue.component('rule-form', {
            template: '#rule-form',
            data: function () {
                return {
                    country_id: null,
                    degree_id: null,
                    examination_map: []
                }
            },
            watch: {
                country_id: function (newVal, oldVal) {
                    this.$http.get('{{route('country_degree_examination_map')}}', {
                        'degree': this.degree_id,
                        'country': newVal
                    }).then(function (response) {
                        this.examination_map = response.data.data;
                    });
                },
                degree_id: function (newVal, oldVal ) {
                    this.$http.get('{{route('country_degree_examination_map')}}', {
                        'degree': newVal,
                        'country': this.country_id
                    }).then(function (response) {
                        this.examination_map = response.data.data;
                    });
                }
            }

        });
    </script>
@endsection
