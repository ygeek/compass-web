@extends('layouts.admin')
@section('content')
    <?php
      if ($type == 'App\College') {
          $college = App\College::find($id);
      } elseif ($type == 'App\Speciality') {
          $speciality = App\Speciality::find($id);
          $college = $speciality->college;
      }
    ?>
      @if(isset($college))
      <h5>{{$college->chinese_name}} @if(isset($speciality)) {{$speciality->name}} @endif 申请要求设置</h5>
    @endif
    <requirement></requirement>
    <template id="tag-setting">
        <button class="btn btn-primary" @click="showPop=true">设置</button>
        <div class="modal in" style="display: block;" v-if="showPop">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="block block-themed block-transparent remove-margin-b">
                        <div class="block-header bg-primary-dark">
                            <ul class="block-options">
                                <li>
                                    <button @click="showPop=false" data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                                </li>
                            </ul>
                            <h3 class="block-title">@{{examination.examination_name}} 设置</h3>
                        </div>
                        <div class="block-content">
                            <table class="table">
                                <tr>
                                    <th>
                                        条件标签名称
                                    </th>
                                    <th>
                                        条件值
                                    </th>
                                    <th>
                                        操作
                                    </th>
                                </tr>

                                <tr v-for="require in examination.requirement">
                                    <td>
                                        <input type="text" v-model="require['tag_name']">
                                    </td>
                                    <td>
                                        <input type="text" v-model="require['tag_value']">
                                    </td>
                                    <td>
                                        <button @click="deleteRequire(require)">删除</button>
                                    </td>
                                </tr>
                            </table>
                            <button @click="addRequireForExamination()">添加 @{{examination.examination_name}} 要求段 </button>
                            @{{reversedExamination}}
                            <textarea v-model="examination_detail" placeholder="please input..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal" @click="showPop=false"><i class="fa fa-check"></i> Ok</button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="requirement">
        <div>
            <a class="btn btn-primary" href="javascript:window.history.back()">返回</a>
            <div class="js-wizard-simple block">
                <!-- Step Tabs -->
                <ul class="nav nav-tabs nav-justified">
                    <li v-for="degree in requirements" v-bind:class="{'active': $index == 0}">
                        <a href="#degree@{{degree.id}}" data-toggle="tab" aria-expanded="false">@{{degree.name}}</a>
                    </li>
                </ul>
                <!-- END Step Tabs -->

                <!-- Steps Content -->
                <div class="block-content tab-content">
                    <div class="tab-pane push-30-t push-50" id="degree@{{degree.id}}" v-for="degree in requirements" v-bind:class="{'active': $index == 0}">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>
                                    要求名称
                                </th>
                                <th>
                                    条件设置
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="examination in degree.examinations">
                                <td>
                                    @{{ examination.examination_name }}
                                </td>
                                <td>
                                    <template v-if="examination.examination_name !== '备注'">
                                      <input type="text" v-model="examination['requirement']"  v-if="!examination.tagable">
                                    </template>

                                    <template v-if="examination.examination_name == '备注'">
                                      <textarea v-model="examination['requirement']"  v-if="!examination.tagable"></textarea>
                                    </template>

                                    <template v-if="examination.tagable">
                                        <tag-setting :examination.sync="examination">
                                        </tag-setting>
                                    </template>

                                    <template v-if="examination.sections.length > 0">
                                        <table>
                                            <tr>
                                                <th>
                                                    子科目名称
                                                </th>
                                                <th>
                                                    子科目分值
                                                </th>
                                            </tr>
                                            <tr v-for="section in examination.sections">
                                                <td>
                                                    @{{section.name}}
                                                </td>
                                                <td>
                                                    <input type="text" v-model="section['requirement']">
                                                </td>
                                            </tr>
                                        </table>
                                    </template>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="block-content block-content-mini block-content-full border-t">
                    <div class="row">
                        <div class="col-xs-6">
                        </div>
                        <div class="col-xs-6 text-right">
                            <button @click="save" class="wizard-finish btn btn-primary" type="submit" style="display: inline-block;"><i class="fa fa-check"></i>保存</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <script>
        Vue.component('tag-setting', {
            template: '#tag-setting',
            props: ['examination'],
            data: function(){
                return {
                    showPop: false,
                    examination_detail: '',
                    examinationScoreArr: []
                }
            },
            computed: {
                // 一个 computed getter
                reversedExamination: {
                // function() {
                //     var self = this;
                //     self.examinationScoreArr = self.examination_detail.split('#');
                //     console.log(self.examinationScoreArr);
                // }

                    get:function(){
                        var self = this;
                        self.examinationScoreArr = self.examination_detail.split('#');
                        self.examination.requirement.forEach(function(item, index) {
                            // self.examination.requirement.push({tag_value: 1});
                            self.examination.requirement[index].tag_value = self.examinationScoreArr[index];
                            return;
                        });
                    }
                }
            },
            methods: {
                addRequireForExamination: function(){
                    var newRequire = {
                        _id: guid(),
                        'tag_name': '',
                        'tag_value': null
                    };
                    if(!this.examination.requirement){
                        this.examination.requirement = [];
                    }

                    this.examination.requirement.push(newRequire);
                },
                deleteRequire: function(require){
                    var _id = require._id;
                    var that = this;
                    this.examination.requirement.forEach(function(item, index){
                        if(item._id == _id){
                            that.examination.requirement.splice(index, 1);
                            return;
                        }
                    });
                }
            }
        });

        Vue.component('requirement', {
            template: '#requirement',
            data: function () {
                return {
                    'requirements': function () {
                        @if(isset($template))
                           return {!! json_encode($template) !!} ;
                        @else
                           return {!! json_encode($requirement) !!} ;
                        @endif
                    }()
                }
            },
            methods: {
                save: function () {
                    var url = "{{ route('admin.requirement.index', ['type' => $type, 'id' => $id]) }}";
                    this.$http.post(url, {'requirement': this.requirements}).then(function(response){
                        alert('保存成功');
                    });
                }
            }
        });
    </script>
@endsection
