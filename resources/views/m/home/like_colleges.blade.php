@include('m.public.header')
<style>
#header{ display: none;}    
</style>
<div class="clear"></div>
<div class="main02">
    
    <div class="header">
        <a href="/home"><div class="header_l"><img src="/static/images/back.png" height="20" /></div></a>
        <div class="header_c">我的收藏</div>
    </div>
    <div class="grzy_wdsc_list">
        <ul>
            @foreach($colleges as $college)
            <div class="pinggu_xx50" >
                <div class="pinggu_xx_name50">
                    <a href="{{route('colleges.show', $college->key)}}" ><h2>
                        <img src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}"><br />
                        <span style="display:block; float:left;">{{$college->chinese_name}}</span><span style="background:#23e6bb;display:block; float:left; color:#fff; border-radius:3px; padding:1% 2%; font-size:0.8em; margin:0 0 0 5px;">{{ ($college->type=="public")?'公立':'私立' }}</span><br />
                        <div class="clear"></div>
                    {{$college->english_name}}
                        </h2></a>
                        <h1>本国排名：{{$college->domestic_ranking}}<br><span style="background:url(/static/images/icon21.jpg) left no-repeat; background-size:20px; padding:0 0 0 20px;">{{$college->administrativeArea->name}}
                                            @if($college->administrativeArea->parent)
                                                , {{$college->administrativeArea->parent->name}}
                                                @if($college->administrativeArea->parent->parent)
                                                    , {{$college->administrativeArea->parent->parent->name}}
                                                @endif
                                            @endif</span><br>
                            <a href="/estimate/step-1?selected_country_id={{$college->country_id}}&college_id={{$college->id}}" style="font-size:1.2em; line-height: 40px;">测试录取率>></a><br>
                       
                            <img src="/static/images/xin<?php if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college->id)){echo 1;} else {echo 2;}}else{echo 2;} ?>.png" width="30" style=" cursor: pointer;" likeid='<?php if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college->id)){echo 1;} else {echo 2;}}else{echo 3;} ?>' onclick="setLikes('{{ $college->id }}',$(this))" ><span id='shuzi{{ $college->id }}'>{{ $college->like_nums }}</span>
                       </h1>
                       <div class="clear"></div>
                </div>     
            </div>
        @endforeach
        
            
            

        </ul>
    </div>
    <div class="clear"></div>
</div>
<script>
    

//收藏与取消
//第一步选择专业
function setLikes(college_id,obj)
{
    var likeid = obj.attr("likeid");
    var shuzi = $("#shuzi"+college_id).html();
    if(likeid=="3")
    {
        changeView('#login');
    }
    if(likeid=="2")
    {
        $.ajax({
            type:'POST',
            url:'/like_college',
            data:'college_id='+college_id,
            async:false,
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            dataType:'json',
            success:function(e){
                if(e.status=="ok")
                {
                    obj.attr("src","/static/images/xin1.png");
                    obj.attr("likeid","1");
                    $("#shuzi"+college_id).html(parseInt(shuzi)+1);
                     alert('收藏成功!');
                }
            }
        }); 
    }
    if(likeid=="1")
    {
        $.ajax({
            type:'POST',
            url:'/dislike_college',
            data:'college_id='+college_id,
            async:false,
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            dataType:'json',
            success:function(e){
                if(e.status=="ok")
                {
                    obj.attr("src","/static/images/xin2.png");
                    obj.attr("likeid","2");
                    $("#shuzi"+college_id).html(parseInt(shuzi)-1);
                    alert('取消成功!');
                    location.href=location.href;
                }
            }
        }); 
    }
    
}
</script>