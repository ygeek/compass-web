@include('m.public.header')
<style>
    .core { display: none;}

.pingguo_meun {
    height: 40px;
    line-height: 40px;
    background: #fff;
    margin: 60px 0 0 0;
    font-size: 1.0em;
    text-align: center;
}
.main01 {
    background: #ebebeb;
    padding: 0 0 5% 0;
}
.pinggu_xx {
    padding: 5%;
    background: #fff;
    margin: 1% 0 0 0;
}
.pinggu_pp h1 {
    float: left;
    width: 40%;
    font-size: 1.7em;
    overflow-x: hidden;display: block;
}
</style>
<div class="clear"></div>
<div class="pingguo_meun">
    <h1>匹配结果</h1>
    <a href="javascript:void(0)" class="pingguo_meun_hover" dclass="sprint" id="pingguo_meun_hover">冲刺院校（{{count($reduce_colleges['sprint'])}}）</a>
    <a href="javascript:void(0)" class="pingguo_meun_hover" dclass="core">核心院校（{{count($reduce_colleges['core'])}}）</a>
</div>
@foreach($reduce_colleges as $reduce_key => $colleges)
<div class="main01 {{$reduce_key}}">
    @foreach($colleges as $k=>$college)
    <div class="pinggu_xx" >
        
        <div class="pinggu_xx_name50">
                <h2><img src="{{app('qiniu_uploader')->pathOfKey($college['college']['badge_path'])}}" width='80%'><br />
                <span style="display:block; float:left;">{{$college['college']['chinese_name']}}</span><span style="background:#23e6bb;display:block; float:left; color:#fff; border-radius:3px; padding:1% 2%; font-size:0.8em; margin:0 0 0 5px;">{{ ($college['college']['type']=="public")?'公立':'私立' }}</span>
                <div class="clear"></div>
                {{$college['college']['english_name']}}</h2>
                <h1>本国排名：{{$college['college']['domestic_ranking']}}<br><span style="background:url(/static/images/icon21.jpg) left no-repeat; background-size:20px; padding:0 0 0 30px;">
                 <?php
                                                        $area = App\AdministrativeArea::where('id',$college['college']['administrative_area_id'])->get();
                                                        echo ($area[0]->name);
                                                        while ($area[0]->parent_id!=null){
                                                            $area = App\AdministrativeArea::where('id',$area[0]->parent_id)->get();
                                                            echo (" , " . $area[0]->name);
                                                        }
                                                    ?>                           </span>
                    
                       <br><br>
                       <img src="/static/images/xin<?php if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college['college']['id'])){echo 1;} else {echo 2;}}else{echo 2;} ?>.png" width="30" style=" cursor: pointer;" likeid='<?php if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college['college']['id'])){echo 1;} else {echo 2;}}else{echo 3;} ?>' onclick="setLike('{{ $college['college']['id'] }}',$(this))" ><span id='shuzi{{ $college['college']['id'] }}'>{{ $college['college']['like_nums'] }}</span>
                
                </h1>
                <div class="clear"></div>
         </div>     
        
        
        <div class="pinggu_pp">
            <h1>{{$college['score']}}%<span>匹配概率</span></h1>
            <h2><a href = "javascript:void(0);" onclick = "document.getElementById('light{{$k}}').style.display = 'block';document.getElementById('fade{{$k}}').style.display = 'block'">查看匹配详情</a></h2>
        </div>
    </div>
    <div id="fade{{$k}}" class="black_overlay"></div>  
        <div id="light{{$k}}" class="white_content">
            <div class="tanchu_content">
                <h1>{{$college['college']['chinese_name']}}的{{ $selected_speciality_name }}专业匹配如下：</h1> 
                <div class="closed01">
                    <a href = "javascript:void(0);" onclick = "closefade('{{$k}}');"><img src="/static/images/icon18.png" width="15"></a>
                </div>
                <div class="clear"></div>
                <div class="tanchu_list">

                    <div class="pinggu_pm10">
                        <em>专业</em>
                        <span>您的成绩</span>
                        <span>{{ $selected_speciality_name }}</span>
                        
                       
                        <div class="clear"></div>
                    </div>
                    @foreach($college['requirement_contrast'] as $key=>$contrast)
                        @if($key<9)
                        <div class="pinggu_pm11 chakangengduo" @if(Auth::check()) style="display:block;" @else style="display:none;" @endif>
                            <em>{{$contrast['name']}}</em>
                            <span>{{$contrast['user_score']}}</span>
                            <span>@if($contrast['require']=='') &nbsp; @else {{$contrast['require']}} @endif</span>


                            <div class="clear"></div>
                        </div>
                      
                        @endif
                    @endforeach
                   
                     
                   
                     
                        <div class="pinggu_pm10 chakangengduo2"  @if(Auth::check()) style="display:none;" @else style="display:block;" @endif>
                            <em style="width:100%;">您好，请 <a href="javascript:changeView2('#login')" style="color:#1ddab0;">登录</a> 以查看更多内容</em>
                           

                            <div class="clear"></div>
                        </div>
                      
                    
                </div> 
                <div class="tanchu_text">
                    {{$college['requirement_contrast'][9]['require']}}
                </div>
                <div class="tanchu_join"><a href="javascript:void();" onclick="addInten({{$college['college_id']}})">加入意向单</a></div>

                <div class="clear"></div>
            </div>

        </div> 

    
    
    
    @endforeach
    <div class="clear"></div>
</div>
@endforeach
<script>
function addInten(n)
{
    
    $.ajax({
        type:'POST',
        url:'/intentions',
        data:'college_id='+n+'&degree_id={{ $selected_degree->id }}&estimate_id={{ $estimate_id }}&speciality_name={{$selected_speciality_name}}',
        async:false,
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
        dataType:'json',
        success:function(e){
            if(e.status == 'ok'){
                //$("input[name='verify_code']").val(e.data.code);
                alert('加入意向单成功!');
                window.location = "/home/intentions";
            }
            
        },
        error:function(){
            @if(Auth::check())
                alert('请求失败!');
            @else
                
                //alert('请先登录!');
                changeView('#login');
                $("#login").attr('islocal','2');
            @endif
           
        }
    });
    
}

function closefade(num)
{
    $("#fade"+num).hide();
    $("#light"+num).hide();
}
</script>
