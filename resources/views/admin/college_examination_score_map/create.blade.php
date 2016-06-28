@extends('layouts.admin')
@section('content')
    <examination-score-map></examination-score-map>

    <template id="examination-score-map">
        <div class="js-wizard-simple block">
            <!-- Step Tabs -->
            <ul class="nav nav-tabs nav-justified">
                <li v-for="examination in examinations">
                    <a href="#examination@{{examination.id}}" data-toggle="tab" aria-expanded="false">@{{examination.name}}</a>
                </li>
            </ul>
            <!-- END Step Tabs -->

                <!-- Steps Content -->
                <div class="block-content tab-content">
                    <div class="tab-pane push-30-t push-50" id="examination@{{examination.id}}" v-for="examination in examinations" track-by="_id">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        分数段
                                    </th>
                                    <template v-if="!examination.multiple_degree">
                                        <th>
                                            分数
                                        </th>
                                    </template>

                                    <template v-if="examination.multiple_degree">
                                        <th v-for="degree in examination.degrees">
                                            @{{ degree.name }}分数
                                        </th>
                                    </template>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="score_section in examination.score_sections" track-by="_id">
                                    <td>
                                        <input v-model="score_section.section" />
                                    </td>

                                    <template v-if="!examination.multiple_degree">
                                        <td>
                                            <input type="text" v-model="score_section.score"/>
                                        </td>
                                    </template>

                                    <template v-if="examination.multiple_degree">
                                        <td v-for="degree in examination.degrees">
                                            <input type="text" v-model="score_section[degree.id + ':score']" />
                                        </td>
                                    </template>
                                    
                                    <td>
                                        <button @click="deleteScoreSection(score_section._id, examination._id)">删除</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button @click="addSectionForExamination(examination)">添加 @{{examination.name}} 分数段 </button>
                    </div>

                </div>

                <div class="block-content block-content-mini block-content-full border-t">
                    <div class="row">
                        <div class="col-xs-6">
                        </div>
                        <div class="col-xs-6 text-right">
                            <button class="wizard-next btn btn-default disabled" type="button" style="display: none;">Next <i class="fa fa-arrow-right"></i></button>
                            <button class="wizard-finish btn btn-primary" type="submit" style="display: inline-block;"><i class="fa fa-check"></i> Submit</button>
                        </div>
                    </div>
                </div>
        </div>
    </template>
    <script>
        var vm = Vue.component('examination-score-map', {
            template: '#examination-score-map',
            data: function () {
                return {
                    examinations: function(){
                        var examinations_template = {!! json_encode($examination_template) !!};
                        var degrees = {!! json_encode($degrees) !!};
                        return examinations_template.map(function(examination_template){
                            var examination = {
                                _id: guid()
                            };
                            examination.id = examination_template.id;
                            examination.name = examination_template.name;
                            examination.multiple_degree = examination_template.multiple_degree;
                            if(examination.multiple_degree){
                                examination.degrees = degrees.map(function(degree){
                                    return {
                                        id: degree.id,
                                        name: degree.name
                                    };
                                });
                            }

                            var score_sections = examination_template.score_sections.map(function(score_section_template){
                                var section = {
                                    _id: guid()
                                };
                                section.section = score_section_template;
                                if(examination.degrees){
                                    examination.degrees.forEach(function(degree){
                                        var key = degree.id + ":score";
                                        section[key] = null;
                                    });
                                }else{
                                    section['score'] = null;
                                }
                                return section;
                            });
                            examination.score_sections = score_sections;
                            return examination;
                        });
                    }()
                };
            },
            methods: {
                deleteScoreSection: function(section_id, examination_id){
                    this.examinations.forEach(function(examination){
                        if(examination._id == examination_id){
                            examination.score_sections.forEach(function(score_section, index){
                                if(score_section._id == section_id){
                                    examination.score_sections.splice(index, 1);
                                    return;
                                }
                            });
                        }
                    });
                },
                addSectionForExamination: function(examination){
                    var section = {
                                    _id: guid()
                                };
                    section.section = null;
                    if(examination.degrees){
                        examination.degrees.forEach(function(degree){
                            var key = degree.id + ":score";
                            section[key] = null;
                        });
                    }else{
                        section['score'] = null;
                    }
                    examination.score_sections.push(section);
                }
            },
            computed: {

            }
        });
    </script>
@endsection
