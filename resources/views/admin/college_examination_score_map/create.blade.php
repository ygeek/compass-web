@extends('layouts.admin')
@section('content')
    <examination-score-map></examination-score-map>

    <template id="examination-score-map">
        <h3>{{$college->chinese_name}}分数映射表</h5>
        <a class="btn btn-primary" href="javascript:window.history.back()">返回</a>
        <div class="js-wizard-simple block">
            <ul class="nav nav-tabs nav-justified">
                <li v-for="examination in examinations" v-bind:class="{'active': $index == 0}">
                    <a href="#examination@{{examination.id}}" data-toggle="tab" aria-expanded="false">@{{examination.name}}</a>
                </li>
            </ul>

                <div class="block-content tab-content">
                    <div class="tab-pane push-30-t push-50" id="examination@{{examination.id}}" v-for="examination in examinations" track-by="_id" v-bind:class="{'active': $index == 0}">
                      <examination-editor
                        :examination.sync="examination"
                      ></examination-editor>
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
    </template>

    <template id="examination-editor">
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
                        <input type="text" v-model="score_section.score" placeholder="@{{examination.name}} @{{score_section['section']}}"/>
                      </td>
                  </template>

                  <template v-if="examination.multiple_degree">
                      <td v-for="degree in examination.degrees">
                        <input type="text" v-model="score_section[degree.id + ':score']" placeholder="@{{examination.name}} @{{score_section['section']}} @{{degree.name}}" />
                      </td>
                  </template>

                  <td>
                      <button @click="deleteScoreSection(score_section._id, examination._id)">删除</button>
                  </td>
              </tr>
          </tbody>
      </table>
      <button @click="addSectionForExamination(examination)">添加 @{{examination.name}} 分数段 </button>

      <div class="row">
        <div class="col-xs-6">
          <div class="form-group">
            <label>文本格式</label>
            <textarea rows="10" cols="70" v-model="textFormat"></textarea>
          </div>
        </div>
      </div>
    </template>

    <script>
    Vue.component('examination-editor', {
      template: '#examination-editor',
      props: ['examination'],
      data: function() {
        return {

        }
      },
      computed: {
        textFormat: {
          get: function(){
            var text = '';
            var that = this;
            this.examination.score_sections.forEach(function(item){
              var values = [];
              console.log(item.section);
              values.push(item.section);
              if(!that.examination.multiple_degree) {
                values.push(item.score);
              }else {
                values.push(item['2:score']);
                values.push(item['3:score']);
              }
              text += values.join("#")
              text += "\n"

            });

            return text;
          },
          set: function(newValue) {
            var multiple_degree = this.examination.multiple_degree;
            var sections = newValue.split("\n").filter(function(item){
              return item != ""
            });

            var newSections = [];
            var that = this;

            sections.map(function(section){
              var section_items = section.split('#');
              var newSection = {
                _id: guid(),
              };
              newSection.section = section_items[0];

              if(multiple_degree) {
                newSection['2:score'] =section_items[1];
                newSection['3:score'] =section_items[2];
              }else {
                newSection.score = section_items[1];
              }

              newSections.push(newSection);
            });

            console.log(this.examination.sections);
            this.examination.score_sections = newSections;
          },
          // set: function(newValue){
          //   var keys = this.keys();
          //
          //   var rank = [];
          //
            // var items = newValue.split("\n").filter(function(item){
            //   return item != ""
            // });
          //

          //
          //   this.rank.rank = rank;
          // }
        }
      }
    });
    Vue.component('examination-score-map', {
      template: '#examination-score-map',
      data: function () {
          return {
              examinations: function(){
                  @if(isset($examination_template))
                      var examinations_template = {!! json_encode($examination_template) !!};
                      var degrees = {!! json_encode($degrees) !!};
                      var examinations = {};

                      examinations_template.forEach(function(examination_template){
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
                          var score_sections = [];
                          if(!!examination_template.score_sections){
                              score_sections = examination_template.score_sections.map(function(score_section_template){
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
                          }

                          examination.score_sections = score_sections;
                          examinations[examination.id] = examination;
                      });
                  @else
                      var examinations = {!! json_encode($map->map) !!};
                  @endif

                  return examinations;
              }()
          };
      },
      methods: {
          deleteScoreSection: function(section_id, examination_id){
              var that = this;
              Object.keys(this.examinations).forEach(function(key){
                  var examination = that.examinations[key];
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
              section.section = '';
              if(examination.degrees){
                  examination.degrees.forEach(function(degree){
                      var key = degree.id + ":score";
                      section[key] = null;
                  });
              }else{
                  section['score'] = null;
              }
              examination.score_sections.push(section);
          },
          save: function(){
              var url = '{{ route("admin.colleges.examination_score_map.index", $college->id) }}';
              this.$http.post(url, {
                  map: this.examinations
              }).then(function(response){
                  alert('修改成功');
              });
          }
      },
  });
    </script>
@endsection
