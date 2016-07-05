@extends('layouts.app')

@section('content')
<step-second-form></step-second-form>
<template id="step-second-form">
     <p>
        <label for="name">姓名</label>
        <input type="text" id="name" v-model="data.name"/>
    </p>


    @if($selected_degree->name == '硕士')
        <p>
            <label for="recently_college_name">最近就读院校</label>
            <input type="text" id="recently_college_name" v-model="data.recently_college_name"/>

            <label for="recently_speciality_name">最近就读专业</label>
            <input type="text" id="recently_speciality_name" v-model="data.recently_speciality_name"/>
        </p>
    @endif

    @if($selected_degree->name == '本科')
        <p>
            <label for="cee">高考</label>
            <select name="cee_province" v-model="data['examinations']['高考']['tag']">
                @foreach(config('provinces') as $province)
                    <option value="{{ $province }}">{{$province}}</option>
                @endforeach
            </select>
            <input type="text" id="cee" name="cee" v-model="data['examinations']['高考']['score_without_tag']"/>
        </p>
    @endif
    <p>
        <label for="mean">平均成绩</label>
        @if($selected_degree->name == '本科')
            <input type="text" id="mean" v-model="data['examinations']['高中平均成绩']['score']"/>
        @else
            <input type="text" id="mean" v-model="data['examinations']['大学平均成绩']['score']"/>
        @endif
    </p>


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

    <p v-for="group in groups">
        <group-examination :group.sync="group"></group-examination>
    </p>

    <button @click="submit">生成择校方案</button>
</template>

<template id="group-examination">
    <label for="group@{{ $index }}">@{{ group.title }}</label>
    <select v-model="group['selected_group']">
        <option v-for="option in group['selects']" value="@{{ option }}">
            @{{ option }}
        </option>
    </select>

    <template v-if="selected_examination">
        <input type="text" v-model="selected_examination.score">

        <template v-for="section in selected_examination['sections']">
            <label for='section@{{ $index }}'>@{{ section.name }}</label>
            <input type="text" v-model="section.score">
        </template>
    </template>
</template>

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
            submit: function(){
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

                this.$http.post("{{ route('estimate.store') }}", {data: this.data}).then(function(response){

                });
            }
        }
    })
</script>
@endsection
