@include('m.public.header')
<style>
#header{ display: none;}    
</style>
<div class="clear"></div>
<div class="main02" style="background: #fff;">
  
    <div class="header">
        <a href="/home"><div class="header_l"><img src="/static/images/back.png" height="20" /></div></a>
        <div class="header_c">我的意向单</div>
    </div>
   
    @foreach($intentions['intentions'] as $intention)
    <div class="pinggu_xx" >
        
        <div class="pinggu_xx_name">
            <img src="{{$intention['badge_path']}}">
            <h1>{{$intention['college']['chinese_name']}}<br>{{$intention['college']['english_name']}}</h1>
            <div class="clear"></div>
        </div>
        
    </div>
    <div class="pinggu_xx" style=" overflow: auto; width:auto;height: 417px; margin-top: 0px; padding-top: 0px; ">
        <div class="pinggu_pm01">
            <em style="border-bottom:none;">&nbsp;</em>
            <em>专业</em>
           
            @foreach($intentions['user_scores'] as $key=>$val )
            @if($key!='备注')
            <span>{{$key}}</span>
            @endif
            @endforeach
            <div class="clear"></div>
        </div>
        <div class="pinggu_pm01">
            <em style="border-bottom:none;">&nbsp;</em>
            <em>您的成绩</em>
           
            @foreach($intentions['user_scores'] as $key=>$val )
            @if($key!='备注')
            <span>{{$val}}</span>
            @endif
            @endforeach
            <div class="clear"></div>
        </div>
        @foreach($intention['specialities'] as $key=>$val )
        <div class="pinggu_pm01">
            <em style="border-bottom:none;"><input type="checkbox" value="{{$val["_id"]}}" onclick="countBox($(this))">&nbsp; <a href="javascript:deleteSpeciality('{{$val["_id"]}}');"><img src="/static/images/icon18.png" width="10" height="10"></a></em>
            <em>{{$val['speciality_name']}}</em>
            @foreach($val['require'] as $k=>$v )
            @if($k!='备注')
            <span>@if($v=='') &nbsp; @else {{$v}} @endif</span>
            @endif
            @endforeach

            
            <div class="clear"></div>
        </div>
        @endforeach
        
    </div> 
    

    @endforeach
   
    <div class="clear"></div>
</div>
<div onclick="subshenhe()" style="position: fixed;bottom: 0px; width: 100%; line-height: 30px; font-size: 1em; text-align: center;color: #fff; background-color: #0e2d60;">提交审核 <span id="ched">0</span>/<?php echo count($intention['specialities']); ?></div>
<script>
function deleteSpeciality(num)
{
    if(!confirm('确定删除专业？(若当前专业为院校最后一个专业、院校也会被删除)')){
        return false;
    }
    
    $.ajax({
        type:'DELETE',
        url:"/intentions/"+num,
        data:'',
        async:false,
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
        dataType:'json',
        success:function(e){
            if(e.status=='ok'){
                alert('删除成功');
                window.location.reload();
            }
        }
    }); 
}

function countBox(obj)
{
    /*$("input[type=checkbox]").each(function(){
        alert($(this).text())
    });*/
    var val = obj.attr('checked');
   
    var num = $("#ched").html();
    if(val=="checked")
    {
        var t = parseInt(num)+1;
        $("#ched").html(t);
    }
    else
    {
        var t = parseInt(num)-1;
        $("#ched").html(t);
    }
}

function subshenhe()
{
    var selected_speciality_ids = new Array();
    var i =0 ;
    $("input[type=checkbox]").each(function(){
        if($(this).attr("checked")=="checked")
        {
            selected_speciality_ids[i] = $(this).val();
        }
        i++;
    });
    var estimate_id = '';
    if(selected_speciality_ids.length == 0){
        alert('未选择审核专业');
        return;
    }
   
}
</script>