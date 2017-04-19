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
@if(!$college_id)
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
                    <h1>本国排名：{{$college['college']['domestic_ranking']}}<br><span style="background:url(/static/images/icon21.jpg) left no-repeat; background-size:20px; padding:0 0 0 30px;">
                     <?php
                        $area = App\AdministrativeArea::where('id',$college['college']['administrative_area_id'])->get();
                        echo ($area[0]->name);
                        while ($area[0]->parent_id!=null){
                            $area = App\AdministrativeArea::where('id',$area[0]->parent_id)->get();
                            echo (" , " . $area[0]->name);
                        }
                    ?>
                    </span>

                   <br><br>
                   <img src="/static/images/xin<?php if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college['college']['id'])){echo 1;} else {echo 2;}}else{echo 2;} ?>.png" width="30" style=" cursor: pointer;" likeid='<?php if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college['college']['id'])){echo 1;} else {echo 2;}}else{echo 3;} ?>' onclick="setLike('{{ $college['college']['id'] }}',$(this))" ><span id='shuzi{{ $college['college']['id'] }}'>{{ $college['college']['like_nums'] }}</span>

                    </h1>

                    <div class="bot">
                      <div class="left" style=" float: left; width: 85%; height: 14px; line-height: 14px; color: #2b426e; text-align: left;">
                          <p style="display:block;">{{$college['college']['chinese_name']}}</p>
                          <p style="font-size:1.0em; width: 100%; margin-bottom: 1em;text-align: left; height: 14px; line-height: 10px;">{{$college['college']['english_name']}}</p>
                      </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
             </div>
            <div class="pinggu_pp" style="margin-top: 7%;">
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
                        <h4 style="color:red;">您的各项成绩如下:</h4>
                        <div class="pinggu_pm10">
                            <em>您的高中成绩(<strong>{{$student_temp_data['province']}}</strong>)</em>
                            <span>高中总分:{{$student_temp_data['score_without_tag']}}</span>
                            <span>高中平均成绩:{{$student_temp_data['high_school_average']}}</span>
                            <div class="clear"></div>
                        </div>
                        <div class="pinggu_pm10">
                            <em>您的雅思成绩</em>
                            <span>雅思:{{$student_temp_data['ielts']}}</span>
                            <div class="clear"></div>
                        </div>
                        <div class="pinggu_pm10">
                            <em>雅思各项成绩</em>
                            <span>听: {{ $student_temp_data['ielts_average'][0]['score'] ? $student_temp_data['ielts_average'][0]['score'] : "暂未填写" }}</span>
                            <span>说: {{$student_temp_data['ielts_average'][1]['score'] ? $student_temp_data['ielts_average'][1]['score'] : "暂未填写" }}</span>
                            <span>读: {{$student_temp_data['ielts_average'][2]['score'] ? $student_temp_data['ielts_average'][2]['score'] : "暂未填写" }}</span>
                            <span>写: {{$student_temp_data['ielts_average'][3]['score'] ? $student_temp_data['ielts_average'][3]['score'] : "暂未填写" }}</span>
                            <div class="clear"></div>
                        </div>
                        @if(Auth::check())
                            <h4 style="color:red;">{{$college['college']['chinese_name']}}的专业要求:</h4>
                            @foreach($reduce_colleges as $reduce_key => $colleges)
                                @foreach($colleges as $k=>$college)
                                    <div class="pinggu_pm10">
                                        <em>雅思成绩</em>
                                        <span><?php echo $college['ielts_requirement'] ? $college['ielts_requirement'] : '暂无数据'; ?></span>
                                        <div class="clear"></div>
                                        <em>托福成绩</em>
                                        <span><?php echo $college['toefl_requirement'] ? $college['toefl_requirement'] : '暂无数据'; ?></span>
                                        <div class="clear"></div>
                                    </div>
                                @endforeach
                            @endforeach
                        @else
                        <div class="pinggu_pm10 chakangengduo2"  @if(Auth::check()) style="display:none;" @else style="display:block;" @endif>
                            <em style="width:100%;">您好，请 <a href="javascript:changeView2('#login')" style="color:#1ddab0;">登录</a> 以查看更多内容</em>


                            <div class="clear"></div>
                        </div>
                        @endif
                    </div>

                    <div class="tanchu_join" style="display:inline-block;text-align:center;width: 100%;color: #ffffff;border: none;background-color: #0e2d60;height: 40px;position: fixed;bottom: 0px; left: 0px; right: 0px; line-height: 40px;"><a href="javascript:void();" onclick="addInten({{$college['college_id']}})" >加入意向单</a></div>

                    <div class="clear"></div>
                </div>

            </div>
        @endforeach
        <div class="clear"></div>
    </div>
    @endforeach

@else
<div class="white_content" style="display: block">
    <div class="tanchu_content">
      <div class="closed01">
          <a href = "javascript:void(0);" onclick="redirect_back()"><img src="/static/images/icon18.png" width="15"></a>
      </div>
        <h1>{{$res['college']['chinese_name']}}的{{ $selected_speciality_name }}专业匹配如下：</h1>

        <div class="clear"></div>
        <div class="tanchu_list">

            <div class="pinggu_pm10">
                <em>专业</em>
                <span>您的成绩</span>
                <span>{{ $selected_speciality_name }}</span>


                <div class="clear"></div>
            </div>
            @foreach($res['requirement_contrast'] as $key=>$contrast)
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

        <div class="tanchu_join"><a href="javascript:void();" onclick="addInten({{$res['college_id']}})">加入意向单</a></div>

        <div class="clear"></div>
    </div>

</div>
@endif
<a  href="{{ URL::route('estimate.step_first') }}" style="display:inline-block;text-align:center;width: 100%;color: #ffffff;border: none;background-color: #0e2d60;height: 40px;position: fixed;bottom: 0px; left: 0px; right: 0px; line-height: 40px;">重新生成解决方案</a>

<script>
function redirect_back() {
  window.location.href = localStorage.getItem('redirect_back');
}

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
