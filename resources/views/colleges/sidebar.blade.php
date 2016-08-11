<?php
    $hot_colleges = App\College::where('hot', true)->get();
    $local_colleges=[];
    if(isset($college)){
        $local_colleges = App\College::where('administrative_area_id', $college['administrative_area_id'])->where('id','<>', $college['id'])->get();
    }
?>

<sidebar-panel></sidebar-panel>
<template id="sidebar-panel-template">

    <div class="college-sidebar">
        <div class="tab">
            <span v-bind:class="{'active': panel == 'local'}" v-on:click="togglePanel('local')" <?=(count($local_colleges)==0)?"style='display: none;'":""?>>同城院校</span>
            <span v-bind:class="{'active': panel == 'hot'}" v-on:click="togglePanel('hot')" <?=(count($local_colleges)==0)?"style='margin: 0 88px;;'":""?>>热门院校</span>
        </div>
        <div v-show="panel == 'local'">
            @if(count($local_colleges) > 0 )
                @foreach($local_colleges as $college)
                    <div class="college-single">
                        <!--<a href="{{route('colleges.show', ['key' => $college->key])}}" target="_blank">
                            <img src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}"/>
                        </a>
                        <div class="separator"></div>-->
                        <div class="row">
                            <a style="color: #6c6c6c" href="{{route('colleges.show', ['key' => $college->key])}}" target="_blank">
                                <span class="name left">{{$college->chinese_name}}</span>
                            </a>

                            <like-college-sidebar
                                    college_id="{{ $college->id }}"
                                    liked="<?php if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college->id)){echo 1;} else {echo 0;}}else{echo 0;} ?>"
                                    like_nums="{{ $college->like_nums }}"
                            ></like-college-sidebar>

                            <!--<span class="right">收藏数：{{$college->like_nums}}</span>-->
                            <!--<span class="right">本国排名：{{$college->domestic_ranking}}</span>-->
                        </div>
                        <!--<div class="row">
                            <span>{{ $college->english_name }}</span>
                        </div>
                        <div class="row">
                            <span class="left">托福：{{ $college->toeflRequirement('本科') }}</span>
                            <span class="right">雅思：{{ $college->ieltsRequirement('本科') }}</span>
                        </div>-->
                    </div>
                @endforeach
            @endif
        </div>
        <div v-show="panel == 'hot'">
            @if(count($hot_colleges) > 0 )
                @foreach($hot_colleges as $college)
                    <div class="college-single">
                        <div class="row">
                            <a style="color: #6c6c6c" href="{{route('colleges.show', ['key' => $college->key])}}" target="_blank">
                                <span class="name left">{{$college->chinese_name}}</span>
                            </a>

                            <like-college-sidebar
                                    college_id="{{ $college->id }}"
                                    liked="<?php if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college->id)){echo 1;} else {echo 0;}}else{echo 0;} ?>"
                                    like_nums="{{ $college->like_nums }}"
                            ></like-college-sidebar>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

</template>

<template id="like-college-sidebar">
    <span v-if="liked == 0" class="right" @click="likeCollege">收藏(@{{like_nums}})</span>
    <span v-if="liked == 1" class="right" @click="dislikeCollege">取消收藏(@{{like_nums}})</span>
</template>

@include('shared.like_college', ['template_name' => 'like-college-sidebar'])

<script>
    Vue.component('sidebar-panel', {
        template: '#sidebar-panel-template',
        data: function(){
            return {
                panel: '<?=(count($local_colleges)>0)?"local":"hot"?>'
            };
        },
        methods: {
            togglePanel: function(panel){
                this.panel = panel;
            }
        }
    });
</script>

