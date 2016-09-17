@include('m.public.header')
<div class="clear"></div>
<div class="main02">
    @include('m.home.editUser')
   <div class="grzy_wdxx_meun">
        <span>
            <a href="{{ route('home.index') }}">消息</a>
            <a  href="{{ route('home.like_colleges') }}" >收藏</a>
            <a id="grzy_wdxx_meun" href="{{ route('home.intentions') }}" >意向单</a>
        </span>
    </div>
   
    @foreach($intentions['intentions'] as $intention)
    <div class="pinggu_xx" >
        <div class="pinggu_xx_name">
            <img src="{{$intention['badge_path']}}">
            <h1>{{$intention['college']['chinese_name']}}<br>{{$intention['college']['english_name']}}</h1>
            <div class="clear"></div>
        </div>
        <div class="pinggu_pm01">
            <em>专业</em>
           
            @foreach($intentions['user_scores'] as $key=>$val )
            @if($key!='备注')
            <span>{{$key}}</span>
            @endif
            @endforeach
            <div class="clear"></div>
        </div>
    </div>
     @foreach($intention['specialities'] as $key=>$val )
    <div class="pinggu_pm02">
        <em>{{$val['speciality_name']}}</em>
        @foreach($val['require'] as $k=>$v )
        @if($k!='备注')
        <span>@if($v=='') &nbsp; @else {{$v}} @endif</span>
        @endif
        @endforeach
      
        <a href="javascript:deleteSpeciality('{{$val["_id"]}}');"><img src="/static/images/icon18.png" width="15" height="15"></a>
        <div class="clear"></div>
    </div>
     @endforeach
    

    @endforeach
   
    <div class="clear"></div>
</div>
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
</script>