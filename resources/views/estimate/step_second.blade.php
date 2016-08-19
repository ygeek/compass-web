@extends('layouts.app')

@section('content')
    <div class="estimate-page">
        <div class="app-content">
            @include('shared.top_bar', ['page' => 'estimate'])
            <step-second-form></step-second-form>
            <template id="step-second-form">
                <div class="estimate-form" style="width: 860px;">
                    <h1>填写个人资料·2/2</h1>
                    <div class="form-group">
                        <label for="name">姓名</label>
                        <input class="estimate-input" type="text" id="name" v-model="data.name"/>
                    </div>


                    @if($selected_degree->name == '硕士')
                        <div class="form-group">
                            <label for="recently_college_name">最近就读院校</label>
                            <select id="recently_college_name" v-model="data.recently_college_name" class="estimate-input">
                                <?php $master_colleges = App\Setting::get('master_colleges', []) ?>
                                @foreach($master_colleges as $college)
                                    <option value="{{ $college }}">{{$college}}</option>
                                @endforeach
                            </select>

                            <label for="recently_speciality_name">最近就读专业</label>
                            <select id="recently_speciality_name" v-model="data.recently_speciality_name" class="estimate-input">
                                <?php $master_speciality = App\Setting::get('master_speciality', []) ?>
                                @foreach($master_speciality as $speciality)
                                    <option value="{{ $speciality }}">{{$speciality}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="related_length_of_working">相关工作年限</label>
                            <input class="estimate-input" type="text" id="related_length_of_working" v-model="data.related_length_of_working"/>
                        </div>
                    @endif

                    @if($selected_degree->name == '本科')
                        <div class="form-group">
                            <label for="cee">高考</label>
                            <div class="estimate-short-select" style="position: relative; top: 15px;">
                                <select name="cee_province" v-model="data['examinations']['高考']['tag']">
                                    <?php
                                        $provinces = collect(config('provinces'))->sortBy(function ($product, $key) {
                                            if ($product==="重庆")
                                                return iconv('UTF-8', 'GBK//IGNORE', "崇庆");
                                            return iconv('UTF-8', 'GBK//IGNORE', $product);
                                        });
                                    ?>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province }}">{{$province}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <input class="estimate-input" style="width: 159px;left:-4px;position: relative;" type="text" id="cee" name="cee" v-model="data['examinations']['高考']['score_without_tag']"/>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="mean">平均成绩</label>
                        @if($selected_degree->name == '本科')
                            <input class="estimate-input" type="text" id="mean" v-model="data['examinations']['高中平均成绩']['score']"/>
                        @else
                            <input class="estimate-input" type="text" id="mean" v-model="data['examinations']['大学平均成绩']['score']"/>
                        @endif
                    </div>


                    <?php
                    $groups = [
                            ['雅思', '托福IBT']
                    ];

                    if($selected_country->name == '美国'){
                        if($selected_degree->name == '硕士'){
                            $groups[] = ['GRE', 'GMAT'];
                        }else if($selected_degree->name == '本科'){
                            $groups[] = ['ACT', 'SAT'];
                        }
                    }

                    $groups = collect($groups)->map(function($items) use ($selected_degree){
                        $examinations = collect($items)->map(function($item) use ($selected_degree){
                            $examination = \App\Examination::where('name', $item)->select(['id', 'name', 'sections', 'multiple_degree'])->first();
                            $res = $examination->toArray();
                            if($examination->multiple_degree){
                                $res['degree'] = $selected_degree->id;
                            }

                            $res['sections'] = collect($res['sections'])->map(function($item){
                                return [
                                        'name' => $item,
                                        'score' => ''
                                ];
                            });
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

                    <div class="form-group" style="margin-top: -12px;" v-for="group in groups">
                        <group-examination :group.sync="group"></group-examination>
                    </div>

                    <a href="{{ route('estimate.step_first') }}"><button class="estimate-button">返回上一步</button></a>
                    <button @click="submit" class="estimate-button">生成择校方案</button>
                </div>

            </template>

            <template id="group-examination">
                <label for="group@{{ $index }}">@{{ group.title }}</label>
                <div class="estimate-short-select" style="position: relative; top: 15px;">
                    <select v-model="group['selected_group']">
                        <option v-for="option in group['selects']" value="@{{ option }}">
                            @{{ option }}
                        </option>
                    </select>

                </div>

                <template v-if="selected_examination">
                    <input class="estimate-input" style="width: 120px;" type="text" v-model="selected_examination.score">

                    <template v-for="section in selected_examination['sections']">
                        <label style="text-align: right; width: 30px;" for='section@{{ $index }}'>@{{ section.name }}</label>
                        <input  class="estimate-input" style="width: 60px;" type="text" v-model="section.score">
                    </template>
                </template>
            </template>
        </div>
    </div>
    {!! Form::open(['route' => 'estimate.store', 'id' => 'estimate-form']) !!}
        <input name="data" type="hidden" id="estimate-form-data"/>
    {!! Form::close() !!}
    <script type="text/javascript">
        Vue.component('group-examination', {
            template: "#group-examination",
            props: ['group'],
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
                }
            }
        });
        Vue.component('step-second-form', {
            template: '#step-second-form',
            data: function(){
                return {
                    groups: {!! json_encode($groups) !!},
                    data: {
                        selected_degree: {{ $selected_degree->id }},
                        selected_country: {{ $selected_country->id }},
                        selected_speciality_name: '{{$selected_speciality_name}}',
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
                        }
                    }
                }
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
            methods: {
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
                    var tmp = eval(document.getElementById('mean')).value;
                    if (!this.checked(tmp, 0, 100, "平均成绩")){
                        event.preventDefault();
                        return ;
                    }

                    @if($estimate_checked==true)
                    if (confirm("您之前的留学评估将会被清空，是否继续？")==false){
                        event.preventDefault();
                        return ;
                    }
                    @endif

                    for (var i in this.groups){
                        var selected_group = this.groups[i].selected_group;
                        for (var j in this.groups[i].examinations){
                            var examination = this.groups[i].examinations[j];
                            if(examination.name == selected_group) {
                                if (examination.name == "雅思") {
                                    if (!(this.checked(examination.score, 0, 9, "雅思成绩") && this.checked(examination.sections[0].score, 0, 9, "雅思成绩") && this.checked(examination.sections[1].score, 0, 9, "雅思成绩") && this.checked(examination.sections[2].score, 0, 9, "雅思成绩") && this.checked(examination.sections[3].score, 0, 9, "雅思成绩"))) {
                                        event.preventDefault();
                                        return;
                                    }
                                }
                                if (examination.name == "托福IBT") {
                                    if (!(this.checked(examination.score, 0, 120, "托福成绩") && this.checked(examination.sections[0].score, 0, 120, "托福成绩") && this.checked(examination.sections[1].score, 0, 120, "托福成绩") && this.checked(examination.sections[2].score, 0, 120, "托福成绩") && this.checked(examination.sections[3].score, 0, 120, "托福成绩"))) {
                                        event.preventDefault();
                                        return;
                                    }
                                }
                                if (examination.name == "ACT") {
                                    if (!(this.checked(examination.score, 0, 36, "ACT成绩") && this.checked(examination.sections[0].score, 0, 36, "ACT成绩"))) {
                                        event.preventDefault();
                                        return;
                                    }
                                }
                                if (examination.name == "SAT") {
                                    if (!(this.checked(examination.score, 0, 2400, "SAT成绩") && this.checked(examination.sections[0].score, 0, 2400, "SAT成绩") && this.checked(examination.sections[1].score, 0, 2400, "SAT成绩") && this.checked(examination.sections[2].score, 0, 2400, "SAT成绩"))) {
                                        event.preventDefault();
                                        return;
                                    }
                                }
                                if (examination.name == "GRE") {
                                    if (!(this.checked(examination.score, 260, 340, "GRE成绩") && this.checked(examination.sections[0].score, 260, 340, "GRE成绩") && this.checked(examination.sections[1].score, 260, 340, "GRE成绩") && this.checked(examination.sections[2].score, 260, 340, "GRE成绩"))) {
                                        event.preventDefault();
                                        return;
                                    }
                                }
                                if (examination.name == "GMAT") {
                                    if (!(this.checked(examination.score, 200, 800, "GMAT成绩") && this.checked(examination.sections[0].score, 200, 800, "GMAT成绩") && this.checked(examination.sections[1].score, 200, 800, "GMAT成绩") && this.checked(examination.sections[2].score, 200, 800, "GMAT成绩"))) {
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
