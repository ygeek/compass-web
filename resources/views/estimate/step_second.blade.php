@extends('layouts.app')

@section('content')
    @if(!(isset($cpm) && $cpm))
        <div class="estimate-page">
            <div class="app-content">
                @include('shared.top_bar', ['page' => 'estimate'])
    @else
         <div class="cpm-estimate-page">
    @endif
            <step-second-form></step-second-form>
            <template id="step-second-form">
                <div class="estimate-form" style="width: 860px;">
                    <h1>填写个人资料·2/2</h1>
                    <div class="form-group estimate-group">
                        <label for="name">姓名<span style="color: red">*</span></label>
                        <input class="estimate-input" type="text" id="name" v-model="data.name"/>
                    </div>

                    @if($selected_degree->name == '硕士')
                        <div class="form-group estimate-group">
                            <label for="recently_college_name">最近就读院校<span style="color: red">*</span></label>
                            <?php $master_colleges = App\Setting::get('master_colleges', []) ?>
                            <?php
                              $index = 0;
                              $user = Auth::user();
                              if($user){
                                $user_recently_college_name = $user->getEstimateInput('recently_college_name');
                              }else{
                                $user_recently_college_name = false;
                              }
                            ?>

                            <input @click="displayCollegeSelect" v-model="data.recently_college_name" class="estimate-input"/>
                            <college-select-pop
                              :show.sync="showCollegeSelect"
                              :default-college="data.recently_college_name"
                              :default-speciality="data.recently_speciality_name"
                              :major-list.sync="majorsList"
                            >
                            </college-select-pop>

                            @if(isset($cpm) && $cpm)
                            </div>
                            <div class="form-group">
                            @endif

                            <label for="recently_speciality_name" @if(!(isset($cpm) && $cpm)) style="margin-left: 50px;" @endif>最近就读专业</label>
                            <?php $master_speciality = App\Setting::get('master_speciality', []) ?>
                            <?php
                              $index = 0;
                              $user = Auth::user();
                              if($user){
                                $user_recently_speciality_name = $user->getEstimateInput('recently_speciality_name');
                              }
                              else{
                                $user_recently_speciality_name = false;
                              }
                            ?>
                            <select id="recently_speciality_name" v-model="data.recently_speciality_name" class="estimate-input">
                              <option
                                v-for="major in majorsList"
                                :value="major"
                                track-by="$index"
                              >
                                @{{major}}
                              </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="related_length_of_working">相关工作年限</label>
                            <input class="estimate-input" type="text" id="related_length_of_working" v-model="data.related_length_of_working"/>
                        </div>
                    @endif

                    @if($selected_degree->name == '本科')
                        <div class="form-group estimate-group">
                            <label for="cee">高考<span style="color: red">*</span></label>
                            <div class="estimate-short-select">
                                <select name="cee_province" v-model="data['examinations']['高考']['tag']">
                                    <?php
                                    $provinces = collect(config('provinces'))->sortBy(function ($product, $key) {
                                        if ($product==="重庆")
                                            return iconv('UTF-8', 'GBK//IGNORE', "崇庆");
                                        return iconv('UTF-8', 'GBK//IGNORE', $product);
                                    });
                                    ?>
                                    <?php
                                      $index = 0;
                                      $user = Auth::user();
                                      if($user){
                                        $user_gaokao_input = $user->getEstimateInput('examinations.高考');
                                      }else{
                                        $user_gaokao_input = false;
                                      }
                                    ?>

                                    @foreach($provinces as $province)
                                        <option value="{{ $province }}" @if($index++ == 0 and !$user_gaokao_input) selected @endif>{{$province}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input class="estimate-input" style="width: 159px;left:-4px;position: relative;" type="text" id="cee" name="cee" v-model="data['examinations']['高考']['score_without_tag']"/>
                            @if(!(isset($cpm) && $cpm))
                            <span style="margin-left: 20px;color: red;">如果没有，请估算一个分值。</span>
                            @endif
                        </div>
                    @endif
                    <div class="form-group estimate-group">
                        <label for="mean">平均成绩<span style="color: red">*</span></label>
                        @if($selected_degree->name == '本科')
                            <input class="estimate-input" type="text" id="mean" v-model="data['examinations']['高中平均成绩']['score']" placeholder="0~100"/>
                        @else
                            <input class="estimate-input" type="text" id="mean" v-model="data['examinations']['大学平均成绩']['score']" placeholder="0~100"/>
                        @endif
                    </div>

                    <?php
                    $groups = [
                            ['雅思', '托福IBT']
                    ];

                    $user = Auth::user();

                    if($selected_country->name == '美国'){
                        if($selected_degree->name == '硕士'){
                            $groups[] = ['GRE', 'GMAT'];
                        }else if($selected_degree->name == '本科'){
                            $groups[] = ['ACT', 'SAT'];
                        }
                    }

                    $groups = collect($groups)->map(function($items) use ($selected_degree, $user){
                        $examinations = collect($items)->map(function($item) use ($selected_degree, $user){
                            $examination = \App\Examination::where('name', $item)->select(['id', 'name', 'sections', 'multiple_degree'])->first();
                            $res = $examination->toArray();
                            if($examination->multiple_degree){
                                $res['degree'] = $selected_degree->id;
                            }

                            $res['sections'] = collect($res['sections'])->map(function($item) use ($user, $examination){

                                $score = '';
                                if($user){
                                  $key = 'examinations.' . $examination->name;
                                  if($user->getEstimateInput($key)){
                                    $ex = $user->getEstimateInput($key);
                                    foreach ($ex['sections'] as $section) {
                                      if($section['name'] == $item){
                                        $score = $section['score'];
                                      }
                                    }
                                  }
                                }

                                return [
                                        'name' => $item,
                                        'score' => $score
                                ];
                            });

                            if($user){
                              $key = 'examinations.' . $examination->name;
                              if($user->getEstimateInput($key)){
                                $res['score'] = $user->getEstimateInput($key)['score'];
                              }
                            }

                            return $res;
                        });

                        $title = collect($items)->implode("/");
                        $selects = collect($items);

                        return [
                                'examinations' => $examinations,
                                'title' => $title,
                                'selects' => $selects
                        ];
                    });

                    ?>

                    <div class="form-group group-examination" v-for="group in groups">
                        <group-examination :group.sync="group" :loop-index="$index"></group-examination>
                    </div>

                    <form action="{{ URL::route('estimate.step_first') }}" method="GET" style="display: none" id="return_form">
                        <input type="hidden" name="selected_country_id" value="{{$selected_country['id']}}">
                        <input type="hidden" name="selected_degree_id" value="{{$selected_degree['id']}}">
                        <input type="hidden" name="selected_year" value="{{$selected_year}}">
                        <input type="hidden" name="selected_category_id" value="{{$selected_category_id}}">
                        <input type="hidden" name="selected_speciality_name" value="{{$selected_speciality_name}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        @if(isset($cpm))
                            <input type="hidden" name="cpm" value="{{ $cpm }}">
                        @endif
                    </form>

                    @if(!$disable_pre_button)
                    <a href="{{ URL::previous() }}">
                      <button class="estimate-button">返回上一步</button>
                    </a>
                    @endif
                    <button @click="submit" class="estimate-button">生成择校方案</button>
                </div>

            </template>


            <template id="group-examination">
                <label for="group@{{ loopIndex }}">@{{ group.title }}<span style="color: red">*</span></label>
                <div class="estimate-short-select">
                    <select v-model="group['selected_group']">
                        <template v-for="option in group['selects']">
                            <option value="@{{ option }}" v-if="option == group['selects'][0]" selected>
                                @{{ option }}
                            </option>
                            <option value="@{{ option }}" v-else>
                                @{{ option }}
                            </option>
                        </template>
                    </select>
                </div>

                <template v-if="selected_examination">
                    <input class="estimate-input" style="width: <?php if(!(isset($cpm) && $cpm)) echo "120px; margin-right: 40px;"; else echo "155px" ?>;" type="text" v-model="selected_examination.score" v-bind:placeholder="default_value">

                    @if(isset($cpm) && $cpm)
                    <div class="form-group section-group" style="display: flex; width: 303px !important; margin-left: 102px;justify-content: space-between;">
                      <template v-for="section in selected_examination['sections']">
                          <div>
                            <label style="text-align: center; width: 30px;" for='section@{{ $index }}'>@{{ section.name }}</label>
                            <input  class="estimate-input section-input" style="width: 30px; margin-right: 0px;" type="text" v-model="section.score">
                          </div>
                      </template>
                    @else
                    <template v-for="section in selected_examination['sections']">
                      <label style="text-align: center; width: 30px;" for='section@{{ $index }}'>@{{ section.name }}</label>
                      <input  class="estimate-input section-input" style="width: 40px;" type="text" v-model="section.score">
                    </template>
                    @endif

                    @if(isset($cpm) && $cpm)
                    </div>
                    @endif

                </template>
            </template>
        </div>
    </div>
            {!! Form::open(['route' => 'estimate.store', 'id' => 'estimate-form']) !!}
    <input name="data" type="hidden" id="estimate-form-data"/>
    @if($college_id)
      <input type="hidden" name="college_id" value="{{ $college_id }}">
    @endif
            @if($cpm)
                <input type="hidden" name="cpm" value="{{ $cpm }}">
            @endif
    {!! Form::close() !!}

    <script type="text/x-template" id="college-select-pop">

      <div class="college-select-pop" v-show="show" @if($cpm) style="width:533px;" @endif>
        <h1>选择院校</h1>
        <div class="close" @click="closeButtonClick">X</div>

        <div class="search-bar">
          <input v-model="searchKeyWord" placeholder="输入院校名称"/>

          <ul class="search-result" v-show="showSearchResult">
            <li v-for="college in showSearchResult">
              <span @click="selectCollege(college)">@{{college.name}}</span>
            </li>
          </ul>
        </div>

        <div class="provinces">
          <ul>
            <li v-for="province in provinces" :class="{ active: province == selectedProvince }">
              <span @click="selectProvince(province)">@{{province}}</span>
            </li>
          </ul>
        </div>

        <div class="colleges">
          <ul>
            <li v-for="college in showColleges">
              <span @click="selectCollege(college)">@{{college.name}}</span>
            </li>
          </ul>
        </div>
      </div>
    </script>


    <script type="text/javascript">

    function merge_options(obj1,obj2){
        var obj3 = {};
        for (var attrname in obj1) { obj3[attrname] = obj1[attrname]; }
        for (var attrname in obj2) { obj3[attrname] = obj2[attrname]; }
        return obj3;
    }

    Vue.component('college-select-pop', {
      template: '#college-select-pop',
      props: ['show', 'defaultCollege', 'defaultSpeciality', 'majorList'],
      data: function() {
        return {
          colleges: [],
          provinces: [],
          loading: true,
          selectedProvince: null,
          searchKeyWord: null,
        }
      },
      methods: {
        closeButtonClick: function() {
          this.searchKeyWord = null;
          this.selectedProvince = null;
          this.$dispatch('close-college-select-pop');
        },
        selectProvince: function(province_name) {
          this.selectedProvince = province_name;
        },
        selectCollege: function(college) {
          this.searchKeyWord = null;
          this.selectedProvince = null;
          this.$dispatch('close-college-select-pop');
          this.$dispatch('select-college', college);
        }
      },
      computed: {
        showSearchResult: function() {
          var that = this;
          if(!this.searchKeyWord) {
            return null;
          }else {
            return this.colleges.filter(function(college) {
              return college.name.indexOf(that.searchKeyWord) !== -1;
            });
          }
        },
        showColleges: function() {
          var that = this;
          if(!that.selectedProvince) {
            return []
          }else {
            return this.colleges.filter(function(college) {
              return college.area == that.selectedProvince;
            });
          }
        },
      },
      created: function() {
        var that = this;
        this.$http.get("{{ route('estimate.select_colleges') }}").then(function(response) {
          that.loading = false;
          that.colleges = response.data.data['colleges'];
          that.provinces = response.data.data['areas'];
          if(that.defaultCollege && that.defaultSpeciality) {
            var college = that.colleges.find(function(college) {
              return college.name == that.defaultCollege;
            });
            that.majorList = college.major.concat('其它');
          }
        });


      },
    });

        Vue.component('group-examination', {
            template: "#group-examination",
            props: ['group', 'loopIndex'],
            data: function(){
                return  {
                    degree_id: {{ $selected_degree->id }}
                };
            },
            computed: {
                selected_examination: function(){
                    var that = this;
                    return this.group.examinations.filter(function(examination){
                        if(examination.name == that.group.selected_group){
                            return true;
                        }
                    }).pop();
                },
                default_value: function () {
                    var examination = this.group.selected_group;
                    if (examination == "雅思") {
                        return "0~9";
                    }
                    if (examination == "托福IBT") {
                        return "0~120";
                    }
                    if (examination == "ACT") {
                        return "0~36";
                    }
                    if (examination == "SAT") {
                        return "0~2400";
                    }
                    if (examination == "GRE") {
                        return "260~340";
                    }
                    if (examination == "GMAT") {
                        return "200~800";
                    }
                }
            }
        });
        Vue.component('step-second-form', {
            template: '#step-second-form',
            data: function(){
                var data = {
                  examinations: {
                      高中平均成绩: {
                          score: ''
                      },
                      大学平均成绩: {
                          score: ''
                      },
                      高考: {
                          score: ''
                      }
                  },
                };

                <?php $user = Auth::user() ?>
                @if($user)
                  @if($user->getEstimateInput('name'))
                    data['name'] = "{{$user->getEstimateInput('name')}}";
                  @endif

                  @if($user->getEstimateInput('examinations.高考'))
                    data['examinations']['高考'] = {!! json_encode($user->getEstimateInput('examinations.高考')) !!};
                  @endif

                  @if($user->getEstimateInput('examinations.高中平均成绩'))
                    data['examinations']['高中平均成绩'] =  {!! json_encode($user->getEstimateInput('examinations.高中平均成绩')) !!};
                  @endif

                  @if($user->getEstimateInput('examinations.大学平均成绩'))
                    data['examinations']['大学平均成绩'] =  {!! json_encode($user->getEstimateInput('examinations.大学平均成绩')) !!};
                  @endif

                  @if($user->getEstimateInput('related_length_of_working'))
                    data['related_length_of_working'] =  {!! json_encode($user->getEstimateInput('related_length_of_working')) !!};
                  @endif

                  @if($user->getEstimateInput('recently_college_name'))
                    data['recently_college_name'] =  {!! json_encode($user->getEstimateInput('recently_college_name')) !!};
                  @endif
                  @if($user->getEstimateInput('recently_speciality_name'))
                    data['recently_speciality_name'] =  {!! json_encode($user->getEstimateInput('recently_speciality_name')) !!};
                  @endif

                @endif

                return {
                    showCollegeSelect: false,
                    majorsList: [],
                    groups: {!! json_encode($groups) !!},
                    data: merge_options({
                        selected_degree: {{ $selected_degree->id }},
                        selected_country: {{ $selected_country->id }},
                        selected_speciality_name: '{{$selected_speciality_name}}'
                    }, data)
                }
            },
            created: function(){
                @if($college_id)
                    parent.window.document.getElementById("estimate_iframe").width='500px';
                parent.window.document.getElementById("estimate_iframe").height='500px';
                parent.window.document.getElementById("position_div").style.top='calc(50% - 250px)';
                parent.window.document.getElementById("position_div").style.right='calc(50% - 250px)';
                @endif
            },
            watch: {
                'data.examinations.高考.score_without_tag': function(newVal, oldVal){
                    if(!this.data.examinations.高考.tag){
                        this.data.examinations.高考.score = '';
                    }else{
                        this.data.examinations.高考.score = this.data.examinations.高考.tag + ":" + newVal;
                    }

                },
                'data.examinations.高考.tag': function(newVal, oldVal){
                    if(!this.data.examinations.高考.score_without_tag){
                        this.data.examinations.高考.score = '';
                    }else{
                        this.data.examinations.高考.score =  newVal + ":" + this.data.examinations.高考.score_without_tag;
                    }
                }
            },
            events: {
              'close-college-select-pop': function() {
                this.showCollegeSelect = false;
              },
              'select-college': function(college) {
                this.data.recently_college_name = college.name;

                if(!college.major) {
                  this.majorsList = ['其它'];
                } else {
                  this.majorsList = college.major.concat(["其它"]);
                }
              }
            },
            methods: {
                displayCollegeSelect: function() {
                  this.showCollegeSelect = true;
                },

                checked: function (tmp, start, end, name) {
                    if (tmp==""){
                        alert(name+"未填写。");
                        return false;
                    }
                    if (isNaN(tmp) || (tmp<start || tmp >end)){
                        alert(name+"应在"+start+"到"+end+"之间。");
                        return false;
                    }
                    return true;
                },
                submit: function(){
                    if(document.getElementById('name').value==""){
                        alert("姓名未填写。");
                        event.preventDefault();
                        return ;
                    }

                    @if($selected_degree->name == '本科')
                    var tmp_1 = eval(document.getElementById('cee')).value;
                    if (!this.checked(tmp_1, 0, 1000, "高考成绩")){
                        event.preventDefault();
                        return ;
                    }
                    @endif

                    var tmp_2 = eval(document.getElementById('mean')).value;
                    if (!this.checked(tmp_2, 0, 100, "平均成绩")){
                        event.preventDefault();
                        return ;
                    }

                    for (var i in this.groups){
                        var selected_group = this.groups[i].selected_group;
                        for (var j in this.groups[i].examinations){
                            var examination = this.groups[i].examinations[j];
                            if(examination.name == selected_group) {
                                if (examination.name == "雅思") {
                                    if (!(this.checked(examination.score, 0, 9, "雅思成绩"))) {
                                        event.preventDefault();
                                        return;
                                    }
                                }
                                if (examination.name == "托福IBT") {
                                    if (!(this.checked(examination.score, 0, 120, "托福成绩"))) {
                                        event.preventDefault();
                                        return;
                                    }
                                }
                                if (examination.name == "ACT") {
                                    if (!(this.checked(examination.score, 0, 36, "ACT成绩"))) {
                                        event.preventDefault();
                                        return;
                                    }
                                }
                                if (examination.name == "SAT") {
                                    if (!(this.checked(examination.score, 0, 2400, "SAT成绩"))) {
                                        event.preventDefault();
                                        return;
                                    }
                                }
                                if (examination.name == "GRE") {
                                    if (!(this.checked(examination.score, 260, 340, "GRE成绩"))) {
                                        event.preventDefault();
                                        return;
                                    }
                                }
                                if (examination.name == "GMAT") {
                                    if (!(this.checked(examination.score, 200, 800, "GMAT成绩"))) {
                                        event.preventDefault();
                                        return;
                                    }
                                }
                            }
                        }
                    }

                    var examinations = {};
                    this.groups.forEach(function(group){
                        var selected_group = group.selected_group;
                        group.examinations.forEach(function(examination){
                            if(examination.name == selected_group){
                                examinations[examination.name] = examination;
                            }
                        });
                    });

                    for (var attrname in examinations) {
                        this.data.examinations[attrname] = examinations[attrname];
                    }

                    var form = document.getElementById('estimate-form');
                    var dataInput = document.getElementById('estimate-form-data');
                    dataInput.value = JSON.stringify(this.data);
                    form.submit();

                    {{--this.$http.post("{{ route('estimate.store') }}", {data: this.data}).then(function(response){--}}

                    {{--});--}}
                }
            }
        })
    </script>
@endsection
