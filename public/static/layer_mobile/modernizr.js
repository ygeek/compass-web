
//View切换
function changeView(newView) {
    $("#content").hide();
    
    $("#header").hide();
    $("#region").hide();
    $("#login").hide();
    
    $(newView).show();
}

function goBlack(newView) {
    
    location.reload();
}

function shaixuan(newView) {
    $("#main02").hide();
    
    $("#header").hide();
    $(".tiaojian").hide();
    $(newView).show();
}
//返回到条件筛选
function goBlack2(newView) {
    
    shaixuan(newView);
}
$(function() {
    //选择国家
    $(".childpar").click(function(){
        var areaid = $(this).attr('area_id');
        if(areaid=='0')
        {
            $('.areachild').show();
            
        }
        else
        {
            $('.areachild').hide();
            $(".area"+areaid).show();
        }
        $('.didian').attr('selected_state_id','0');
        $('.didian').attr('selected_state_id_name','');
        $('.childpar').attr('id','');
        $(this).attr('id','main03_l_menu');
        $('.didian').attr('selected_country_id',areaid);
        $('.didian').attr('selected_country_id_name',$(this).html());
        
    });
    $(".areachilds").click(function(){
        var areaid = $(this).attr('childarea_id');
        
        
        $('.areachilds').attr('id','');
        $(this).attr('id','main03_r_menu');
        $('.didian').attr('selected_state_id',areaid);
        $('.didian').attr('selected_state_id_name',$(this).html());
        if(areaid=='0')
        {
           
            $('.didian').attr('selected_state_id_name','');
        }
    });
    $(".zhuanyechilds").click(function(){
        var areaid = $(this).attr('cateid');
        
        
        $('.zhuanyechilds').attr('id','');
        $(this).attr('id','yuanxiao_sx');
        $('.zhuanye').attr('selected_speciality_cateogry_id',areaid);
        $('.zhuanye').attr('selected_speciality_cateogry_id_name',$(this).html());
        
    });
    $(".leixingchilds").click(function(){
        var areaid = $(this).attr('leiid');
        
        
        $('.leixingchilds').attr('id','');
        $(this).attr('id','yuanxiao_sx');
        $('.leixing').attr('selected_property',areaid);
        $('.leixing').attr('selected_property_name',$(this).html());
        
    });
    
    $(".paimingchilds").click(function(){
        var start = $(this).attr('rank_start');
        var end = $(this).attr('rank_end');
        
        $('.paimingchilds').attr('id','');
        $(this).attr('id','yuanxiao_sx');
        $('.paiming').attr('rank_start',start);
        $('.paiming').attr('rank_end',end);
        if(start=='0'||end=='0')
        {
            $('.paiming').attr('rank_start','');
            $('.paiming').attr('rank_end','');
        }
        var phtm = $(this).html();
        $('.paiming').attr('paiminghtm',phtm);
    });
    //登录与注册
    $(".toLogin").click(function(){
        var phone_number = $("input[name='phone_number']").val();
        var password = $("input[name='password']").val();
        if(!checkSubmitMobil(phone_number)) return false;
        if(password=='')
        {
            alert('请输入密码!');return false;
        }
        $.ajax({
            type:'POST',
            url:'/auth/login',
            data:'phone_number='+phone_number+'&password='+password,
            async:false,
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            dataType:'json',
            success:function(e){
                if(e.status == 'ok'){
                    //location.href = location.href;
                    alert('登录成功!');
                    $("#content").show();
    
                    $("#header").show();
                    $("#login").hide();
                    $(".dl-menu").append('<li class="headuser"><a href="/home"  >个人中心</a></li><li class="headuser"><a href="/auth/logout"  >退出</a></li>');
                    $(".headlogin").remove();
                    //隐藏菜单栏
                    $("#dl-menu-button").removeClass("dl-active");
                    $(".dl-menu").removeClass("dl-menuopen");
                    $(".dl-menu").addClass("dl-menu-toggle");
                }
                
                if(e.status == 'error')
                {
                    alert('登录失败!');
                }
            }
        });
    });
    //注册
    $(".toRegion").click(function(){
        var phone_number = $("input[name='zcphone_number']").val();
        var password = $("input[name='zcpassword']").val();
        if(!checkSubmitMobil(phone_number)) return false;
        if(password=='')
        {
            alert('请输入密码!');return false;
        }
        $.ajax({
            type:'POST',
            url:'/auth/register',
            data:'phone_number='+phone_number+'&password='+password,
            async:false,
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            dataType:'json',
            success:function(e){
                if(e.status == 'ok'){
                    location.href = location.href;
                }
                else
                {
                    alert('登录失败!');
                }
            }
        });
    });
    //获取验证码
    
    
    $(".makePlan").click(function(){
        var name = $("input[name='name']").val();
        
        $('.gkscore').val($('.gktag').val()+':'+$('.gkwithout').val());
        
       // $('#stepSecondPost').submit();
    });
    
    $(".pingguo_meun_hover").click(function(){
        $(".pingguo_meun_hover").not(this).attr("id",'');
        $(this).attr("id",'pingguo_meun_hover');
        $("."+$(this).attr("dclass")).show();
        $("."+$(".pingguo_meun_hover").not(this).attr("dclass")).hide();
    });
});

//确认选择返回
function queding(classStr,valStr)
{
    var htm = '';
    if(classStr=='didian')
    {
        //赋值input
        assignValue('selected_country_id',$('.didian').attr('selected_country_id'));
        assignValue('selected_state_id',$('.didian').attr('selected_state_id'));
        htm = $('.didian').attr('selected_country_id_name') +' '+ $('.didian').attr('selected_state_id_name');
        $(".yuanxiao_sx .paimingchilds").hide();
        $(".yuanxiao_sx .country"+$('.didian').attr('selected_country_id')).attr("style","display:block;");
    }
    else
    {
        assignValue(valStr,$('.'+classStr).attr(valStr));
        htm = $('.'+classStr).attr(valStr+'_name')
    }
    $('.'+classStr+'a').html(htm);
    goBlack2('.shaixuan');
}
//赋值input
function assignValue(valName,valStr)
{
    $('input[name="'+valName+'"]').val(valStr);
}

function pmqueding()
{
    var htm = '';
    assignValue('rank_start',$('.paiming').attr('rank_start'));
    assignValue('rank_end',$('.paiming').attr('rank_end'));
    htm = $('.paiming').attr('paiminghtm');
    $('.paiminga').html(htm);
   
    goBlack2('.shaixuan');
}

function pmqueding2()
{
    var htm = '';
    assignValue('rank_start',$('.start').val());
    assignValue('rank_end',$('.end').val());
    htm = $('.start').val()+'-'+$('.end').val();
    if($('.start').val()||$('.end').val())
    {
        $('.paiminga').html(htm);
    }
    $(".yuanxiao_sx a ").attr("id",'');
    $('.paiming').attr('paiminghtm','不限');
    $('.paiming').attr('rank_start','');
    $('.paiming').attr('rank_end','');
    goBlack2('.shaixuan');
}


function showPm()
{
    $(".xyxiangqing").hide();
    $("#header").hide();
    $('.yxpaiming').show();
}

function gobackCel()
{
     $(".xyxiangqing").show();
    $("#header").show();
    $('.yxpaiming').hide();
}




function checkSubmitEmail(str) {
    if (str == "") {
        alert("邮箱不能为空!") 
        return false;
    }
    if (!str.match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)) {
        alert("邮箱格式不正确");
        return false;
    }
    return true;
}

//jquery验证手机号码 
function checkSubmitMobil(str) {
    if (str == "") {
        alert("手机号码不能为空！");
        return false;
    }

    if (!str.match(/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1}))+\d{8})$/)) {
        alert("手机号码格式不正确！");
        return false;
    }
    return true;
} 


//第一步选择专业
function changeZy()
{
    var country_id = $("select[name='selected_country_id']").val();
    var degree_id = $("select[name='selected_degree_id']").val();
    var category_id = $("select[name='speciality_category_id']").val();
    var num = parseInt(category_id) - 1 ;
    $("select[name='speciality_name']").html('');
    $.ajax({
        type:'GET',
        url:'/estimate/get_speciality',
        data:'country_id='+country_id+'&degree_id='+degree_id+'&category_id='+category_id,
        async:false,
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
        dataType:'json',
        success:function(e){
            var htm = '';
            console.log(e);
            for(var i=0;i<e[num].specialities.length;i++){
               
                htm += '<option value="'+e[num].specialities[i]['name']+'" >'+e[num].specialities[i]['name']+'</option>';
            } 
           
            console.log(htm);
            $("select[name='speciality_name']").html(htm);
        }
    }); 
}

function choseInput(v,n)
{
    var sval = v.val();
  
    $('.st'+n).hide();
    $('.yt'+n+sval).show();
    console.log(n+sval);
}


//收藏与取消
//第一步选择专业
function setLike(college_id,obj)
{
    var likeid = obj.attr("likeid");
    var shuzi = $("#shuzi"+college_id).html();
    if(likeid=="3")
    {
        alert('请先登录!');
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
                }
            }
        }); 
    }
    
}

function validatemobile(mobile) 
   { 
       if(mobile.length==0) 
       { 
          alert('请输入手机号码！'); 
       
          return false; 
       }     
       if(mobile.length!=11) 
       { 
           alert('请输入有效的手机号码！'); 
          
           return false; 
       } 
        if(!(/^1(3|4|5|7|8)\d{9}$/.test(mobile))){ 
            alert("请输入有效的手机号码!");  
            return false; 
        } 
      return true;
   } 