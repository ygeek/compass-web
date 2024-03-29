@extends('layouts.admin')
@section('content')
    <rules-index></rules-index>
    <template id="rule-index">
        <div v-show="onloading" style="z-index: 9999; display: block; position: fixed; width: 100%; height: 100vh; background: rgba(0, 0, 0, 0.5); top: 0; left: 0">
          <span style="left: 50%; top:50%;position: absolute; display: block; color: #fff;">加载中。。。</span>
        </div>
        <div class="block">
            <div class="block-header">
                <h3 class="block-title">规则列表</h3>
                <a class="btn btn-primary" href="{{route('admin.examination_score_weights.create')}}">新增规则</a>
            </div>

            <div class="modal" tabindex="-1" style="display: block;" v-if="showPop">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="block block-themed block-transparent remove-margin-b">
                            <div class="block-header bg-primary-dark">
                                <ul class="block-options">
                                    <li>
                                        <button data-dismiss="modal" @click="this.showPop = false" type="button"><i class="si si-close"></i></button>
                                    </li>
                                </ul>
                                <h3 class="block-title">院校关联设置</h3>
                            </div>
                            <div class="block-content">
                                <div class="checkbox" v-for="college in colleges">
                                    <label for="college-@{{college.id}}">
                                        <input type="checkbox"
                                               id="college-@{{college.id}}"
                                               v-model="college.checked"
                                        > @{{college.chinese_name}}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-sm btn-default" @click="this.showPop = false" type="button" data-dismiss="modal">Close</button>
                            <button class="btn btn-sm btn-primary" @click="confirmChange" type="button" data-dismiss="modal"><i class="fa fa-check"></i> Ok</button>
                        </div>
                    </div>
                </div>
            </div class="modal">

            <div class="block-content">
                <table class="table table-striped table-borderless table-header-bg">
                    <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th >规则名称</th>
                        <th >国家</th>
                        <th >学历</th>
                        <th class="text-center" style="width: 100px;">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($weights as $weight)
                        <tr>
                            <td class="text-center">{{$weight->id}}</td>
                            <td>{{$weight->name}}</td>
                            <td>{{$weight->country->name}}</td>
                            <td>{{$weight->degree->name}}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a @click="loadColleges({{$weight->id}})" class="btn btn-xs btn-default">
                                      修改院校
                                    </a>

                                    <a href="{{ route('admin.examination_score_weights.edit', $weight->id) }}" class="btn btn-xs btn-default">
                                      修改规则
                                    </a>

                                    <form action="{{ URL::route('admin.examination_score_weights.destroy', $weight->id) }}" method="POST" onsubmit="return ConfirmDelete()">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button class="btn btn-danger btn-xs">删除规则</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $weights->render() }}
            </div>
        </div>
    </template>


    <script>
        var vm = Vue.component('rules-index', {
            template: '#rule-index',
            data: function () {
                return {
                    showPop: false,
                    colleges: [],
                    rule_id: null,
                    onloading: false,
                };
            },
            methods:{
                loadColleges: function(rule_id){
                    var url = '{{route('admin.examination_score_weights.colleges', ['weight_id' => 'weight_id'])}}';
                    url = url.replace('weight_id', rule_id);
                    this.onloading = true;
                    this.$http.get(url).then(function (response) {
                        var data = response.data.data;
                        data.map(function(college){
                            if(college.examination_score_weight_id == rule_id){
                                college.checked = true;
                            }else{
                                college.checked = false;
                            };
                            return college;
                        });
                        this.colleges = data;
                        this.showPop = true;
                        this.rule_id = rule_id;
                        this.onloading = false;
                    });
                },
                confirmChange: function(){
                    var data = this.colleges.map(function(college){
                       return {
                           id: college.id,
                           checked: college.checked
                       };
                    });

                    var url = '{{route('admin.examination_score_weights.updateColleges', ['weight_id' => 'weight_id'])}}';
                    url = url.replace('weight_id', this.rule_id);
                    this.$http.patch(url, JSON.stringify(data)).then(function(respose){
                        this.showPop = false;
                    });
                }
            }
        });
    </script>
@endsection
