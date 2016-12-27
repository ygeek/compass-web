<?php
    $hot_colleges = App\College::where('hot', true)->get();
    $local_colleges=[];
    if(isset($college)){
        $local_colleges = App\College::where('administrative_area_id', $college['administrative_area_id'])->where('id','<>', $college['id'])->get();
    }
    
  
?>
<div class="main05" >
    <div class="tab" >
        <div class="tabli" show="tabli1"><a  class=" act">热门院校</a></div>
        <div class="tabli"  show="tabli2"><a  class="">同城院校</a></div>
        <div class="clear"></div>
    </div>
    <div  class="lbe tabli1 " >
        <div class="yuanxiao_gl">
            
            @foreach($hot_colleges as $college)
            <a href="{{route('colleges.show', $college->key)}}" ><p style=" padding-top: 3%; padding-bottom: 3%;">{{$college->chinese_name}}</p></a>
           
            @endforeach
        </div>
       
    </div>
    <div  class="lbe tabli2 " style="overflow: hidden; " >
        <div class="yuanxiao_gl">
            
            @foreach($local_colleges as $college)
            <a href="{{route('colleges.show', $college->key)}}" ><p style=" padding-top: 3%; padding-bottom: 3%;">{{$college->chinese_name}}</p></a>
            @endforeach
        </div>
      
    </div>
</div>
<script>
   
$(function() {
    //选择国家
    $(".tabli").click(function(){
        var tablinum = $(this).attr("show");
        $(".lbe").hide();
        $("."+tablinum).show();
        $(this).children("a").addClass("act");
        $(".tabli").not(this).children("a").removeClass("act");
    })
    
})
$(".tabli2").hide();
</script>