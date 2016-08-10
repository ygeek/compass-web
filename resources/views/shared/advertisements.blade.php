<?php
    $advertisements = App\Advertisement::where($tag, true)->orderBy('priority', 'asc')->get();
?>

<advertisement-sidebar-panel></advertisement-sidebar-panel>
<template id="advertisement-sidebar-panel-template">

    @if(!isset($test_rate)||$test_rate==true)
        <div class="college-sidebar">
            <div class="college-single">
                <a href="#" target="_blank">
                    <img src="/images/test_rate.jpg" style="height: 156px"/>
                </a>
            </div>
        </div>
    @endif

    @if(!isset($test_rate)||$test_rate==true)
        <?php
            $rankings = App\Setting::get('rankings');
            function echoRank($rankings){
                foreach ($rankings as $ranking){
                    if (isset($ranking['checked'])){
                        echo "<a href='".route('colleges.rank', ['category_id' => $ranking['_id']])."' class='level-2' >".$ranking['name']."</a>";
                    }
                    if (count($ranking['children'])>0){
                        echoRank($ranking['children']);
                    }
                }
            }
            ?>
            <div class="college-sidebar">
                <div class="rank-left">
                    <div class="rank-list" style="margin:0">
                        <span style="font-weight: bold" class="level-1">大学排行榜</span>
                        <?php echoRank($rankings['categories']) ?>
                    </div>
                </div>
            </div>
    @endif

    @if(count($advertisements) > 0 )
        @foreach($advertisements as $advertisement)
            <div class="college-sidebar">
                <div class="college-single">
                    <a href="{{ $advertisement->link }}" target="_blank">
                        <img src="{{app('qiniu_uploader')->pathOfKey($advertisement->background_image_path)}}" style="height: auto"/>
                    </a>
                </div>
            </div>
        @endforeach
    @endif

</template>

<script>
    Vue.component('advertisement-sidebar-panel', {
        template: '#advertisement-sidebar-panel-template'
    })
</script>

