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
                {!! Form::open(['route' => ['admin.examination_score_weights.update', $weight], 'method' => 'PATCH', 'class'=> 'form-horizontal']) !!}

                    <div class="form-group">
                        <label class="col-xs-12" for="name">规则名称<span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" id="name" name="name" placeholder="规则名称" value="{{$weight->name}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-12" for="country">所属国家</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="country" disabled>
                                <option>{{$weight->country->name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-12" for="degree">学历</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="degree" disabled>
                                <option>{{$weight->degree->name}}</option>
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
                    <div class="form-group">
                        <p v-show="!dataValidate()">总和不等于100% 无法提交</p>
                        <button type="submit" class="btn" :disabled="!dataValidate()">提交</button>
                    </div>
                </div>
                {!! Form::close() !!}
        </div>
    </template>
    <script>
        var vm = Vue.component('rule-form', {
            template: '#rule-form',
            data: function () {
                return {
                    name:  "{{$weight->name}}",
                    examination_map: {!! json_encode($weight->weights) !!}
                }
            },methods: {
                dataValidate: function(){
                    var sum = 0;
                    this.examination_map.forEach(function(weight){
                        sum += parseInt(weight.weight);
                    });

                    if(sum == 100){
                        return true;
                    }

                    return false;
                }
            },
        });
    </script>
@endsection