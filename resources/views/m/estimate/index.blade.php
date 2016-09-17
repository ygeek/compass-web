@include('m.public.header')
<style>
    .core { display: none;}
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
        <div class="pinggu_xx_name">
            <img src="{{app('qiniu_uploader')->pathOfKey($college['college']['badge_path'])}}">
            <h1>{{$college['college']['chinese_name']}}<br>{{$college['college']['english_name']}}</h1>
            <div class="clear"></div>
        </div>
        <div class="pinggu_pm">
            <span>托福<br>{{ $college['toefl_requirement'] }}</span>
            <span>雅思<br>{{ $college['ielts_requirement'] }}</span>
            
            <span>U.S.New排名<br>{{$college['college']['us_new_ranking']}}</span>
            <span>Times排名<br>{{$college['college']['times_ranking']}}</span>
            <span>QS排名<br>{{$college['college']['qs_ranking']}}</span>
            <span>本国排名<br>{{$college['college']['domestic_ranking']}}</span>
            <div class="clear"></div>
        </div>
        <div class="pinggu_pp">
            <h1>{{$college['score']}}%<span>匹配概率</span></h1>
            <h2><a href = "javascript:void(0)" onclick = "document.getElementById('light').style.display = 'block';document.getElementById('fade{{$k}}').style.display = 'block'">查看匹配详情</a></h2>
        </div>
    </div>
    <div id="fade{{$k}}" class="black_overlay"></div>  
        <div id="light" class="white_content">
            <div class="tanchu_content">
                <h1>{{$college['college']['chinese_name']}}的{{ $selected_speciality_name }}专业匹配如下：</h1> 
                <div class="closed01">
                    <a href = "javascript:void(0)" onclick = "document.getElementById('light').style.display = 'none';document.getElementById('fade{{$k}}').style.display = 'none'"><img src="/static/images/icon18.png" width="15"></a>
                </div>
                <div class="clear"></div>
                <div class="tanchu_list">

                    <div class="pinggu_pm10">
                        <em>专业</em>
                        @foreach($college['requirement_contrast'] as $key=>$contrast)
                        @if($key<9)
                        <span>{{$contrast['name']}}</span>
                        @endif
                        @endforeach
                       
                        <div class="clear"></div>
                    </div>
                    <div class="pinggu_pm11">
                        <em>你的成绩</em>
                        @foreach($college['requirement_contrast'] as $key=>$contrast)
                        @if($key<9)
                        <span>{{$contrast['user_score']}}</span>
                        @endif
                        @endforeach
                       
                      
                        <div class="clear"></div>
                    </div>
                    <div class="pinggu_pm10">
                        <em>{{ $selected_speciality_name }}</em>
                        @foreach($college['requirement_contrast'] as $key=>$contrast)
                        @if($key<9)
                        <span>@if($contrast['require']=='') &nbsp; @else {{$contrast['require']}} @endif</span>
                        @endif
                        @endforeach
                       
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
                
                alert('请先登录!');
                changeView('#login');
            @endif
           
        }
    });
    
}
</script>
