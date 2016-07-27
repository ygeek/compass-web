<?php
    $hot_colleges = App\College::where('hot', true)->get();
    $local_colleges = App\College::where('administrative_area_id', $area_id)->get();
?>

<sidebar-panel></sidebar-panel>
<template id="sidebar-panel-template">

        <div class="college-sidebar">
            <div class="tab">
                <span v-bind:class="{'active': panel == 'local'}" v-on:click="togglePanel('local')">同城院校</span>
                <span v-bind:class="{'active': panel == 'hot'}" v-on:click="togglePanel('hot')">热门院校</span>
            </div>
            <div v-show="panel == 'local'">
                @if(count($local_colleges) > 0 )
                    @foreach($local_colleges as $college)
                        <div class="college-single">
                            <a href="{{route('colleges.show', ['key' => $college->key])}}" target="_blank">
                                <img src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}"/>
                            </a>
                            <div class="separator"></div>
                            <div class="row">
                                <a style="color: #6c6c6c" href="{{route('colleges.show', ['key' => $college->key])}}" target="_blank">
                                    <span class="name left">{{$college->chinese_name}}</span>
                                </a>

                                <span class="right">本国排名：{{$college->domestic_ranking}}</span>
                            </div>
                            <div class="row">
                                <span>{{ $college->english_name }}</span>
                            </div>
                            <div class="row">
                                <span class="left">托福：{{ $college->toeflRequirement('本科') }}</span>
                                <span class="right">雅思：{{ $college->ieltsRequirement('本科') }}</span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div v-show="panel == 'hot'">
                @if(count($hot_colleges) > 0 )
                    @foreach($hot_colleges as $college)
                        <div class="college-single">
                            <a href="{{route('colleges.show', ['key' => $college->key])}}" target="_blank">
                                <img src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}"/>
                            </a>
                            <div class="separator"></div>
                            <div class="row">
                                <a style="color: #6c6c6c" href="{{route('colleges.show', ['key' => $college->key])}}" target="_blank">
                                    <span class="name left">{{$college->chinese_name}}</span>
                                </a>

                                <span class="right">本国排名：{{$college->domestic_ranking}}</span>
                            </div>
                            <div class="row">
                                <span>{{ $college->english_name }}</span>
                            </div>
                            <div class="row">
                                <span class="left">托福：{{ $college->toeflRequirement('本科') }}</span>
                                <span class="right">雅思：{{ $college->ieltsRequirement('本科') }}</span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

</template>

<script>
    Vue.component('sidebar-panel', {
        template: '#sidebar-panel-template',
        data: function(){
            return {
                panel: 'local'
            };
        },
        methods: {
            togglePanel: function(panel){
                this.panel = panel;
            }
        }
    })
</script>

