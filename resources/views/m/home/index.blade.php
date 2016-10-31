@include('m.public.header')
<style>
#header{ display: none;}    
</style>
<div class="main02html">
    <div class="main02" style=" background: #fff;">
   @include('m.home.editUser')
    
    <div class="clear"></div>
</div>
<div class="clear"></div>
</div>

<link rel="stylesheet" href="/static/mmenu/demo.css?v=5.7.1" />
<link rel="stylesheet" href="/static/mmenu/css/jquery.mmenu.all.css?v=5.7.1" />
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" />
		
<style type="text/css">
    #menu {
        min-width: none;
        max-width: none;
        width: 100%;
        height: 100%;
       
    }
    #inp-name{
        box-sizing: border-box;
        border-radius: 5px;
        text-transform: none;
        text-indent: 0;
       
        vertical-align: middle;
        line-height: 20px;
        display: block;
        height: 40px;
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        float: right;
    }
    #inp-edit{
        top:30%;
    }
    #content { min-height: 600px;}
    .mm-menu.mm-theme-white .mm-btn::after{border:none; text-align: left;}
    .mm-navbar .mm-btn:last-child { text-align: left; width: 60px;;}
    .mm-navbar { height: 60px; line-height: 40px; font-size: 1.3em;}
    .mm-hasnavbar-top-1 .mm-panels { top:60px;}
    .mm-listview { line-height: 30px;}
    .mm-navbar .mm-btn:first-child { height:60px;}
    .mm-menu > .mm-navbar { background:#1ddab0;  color: #fff;}
    .mm-menu.mm-theme-white .mm-navbar a, .mm-menu.mm-theme-white .mm-navbar > * { color: #fff;}
    .mm-menu.mm-theme-white .mm-btn::before { boder-color:rgba(255,255,255,255);}
    .mm-menu.mm-theme-white .mm-btn::after, .mm-menu.mm-theme-white .mm-btn::before {
    border-color: rgba(255,255,255,1);
}
.mm-hasnavbar-top-4 .mm-panels { top:300px;}
    .mm-listview > li:not(.mm-divider)::after { left: 0px;}
    .mm-listview .mm-next::before { border: none;}
    #edit { line-height: 30px;width: 30%;background-color: #0e2d60;color: #fff;text-align: center;padding-top: 0px;top: 40%; cursor: pointer;}
    #locations .edit::after { border: none;} 
    #editbase .edit::after { border: none;} 
    #editpwd .edit::after { border: none;}
    #editmobile .edit::after { border: none;} 
    #upload { 
       
        border-radius: 5px;
        text-transform: none;
        text-indent: 0;
       
        vertical-align: middle;
        line-height: 20px;
        display: block;
        height: 40px;
        width: 60%;
        padding: 10px;
      
        float: right;
    }
</style>
<script>
    
function mmenuShow(conid)
{
    $('#menu').show();
    $("#header").hide();
    $('body,html').animate({ scrollTop: 0 }, 1);
}

</script>
<script type="text/javascript" src="/static/mmenu/js/jquery.mmenu.all.min.js?v=5.7.1"></script>
<script type="text/javascript">
$(function() {
    var headhtml = $('.main02html').html();
    $("#menu")
        .mmenu({
            offCanvas	: false,

            extensions	: ["theme-white"],

            navbar		: {
                title	: "个人中心"
            },

           navbars		: [{
						content 	: [ "prev", "title", "next" ]
					}, {
						content 	: headhtml,
						height 		: 3
					}],
            

            onClick		: {
                setSelected	: false
            }},{})
        .on( 
            'click',
            'a[href^="#/"]',
            function() {
                alert( "Thank you for clicking, but that's a demo link." );
                    return false;
            }
        );
});
function searSub()
{
    $("form:last").submit();
}
function editpwd()
{
    var old_password = $("input[name=userold_password]").val();
    var password = $("input[name=userpassword]").val();
    var _token = $("input[name=_token]").val();
    var password_confirmation = $("input[name=userpassword_confirmation]").val();
    if(old_password==''||password==''||password_confirmation=='')
    {
        alert('请填写完整!');
        return false;
    }
    if(password!=password_confirmation)
    {
        alert('两次密码不一致!');
        return false;
    }
    $.ajax({
            type:'POST',
            url:'/home/change_password',
            data:'old_password='+old_password+'&password='+password+'&password_confirmation='+password_confirmation+'&_token='+_token+'&api=true',
            async:false,
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            dataType:'json',
            success:function(e){
                if(e.status=='error')
                {
                    alert(e.data.message);
                }
                if(e.status=='ok')
                {
                    alert('修改成功!');
                    location.reload() ;
                }
            }
        }); 
}
function editmobile()
{
    var mobile = $(".editmobile").val();
    var code = $(".editverify_code").val();
    var _token = $("input[name=_token]").val();
    
    if(mobile==''||code=='')
    {
        alert('请填写完整!');
        return false;
    }
    
    $.ajax({
            type:'POST',
            url:'/home/change_phone',
            data:'phone_number='+mobile+'&code='+code+'&_token='+_token+'&api=true',
            async:false,
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            dataType:'json',
            success:function(e){
                console.log(e);
                if(e.status=='error')
                {
                    alert(e.data.message);
                }
                if(e.status=='ok')
                {
                    alert('修改成功!');
                    location.reload() ;
                }
            }
        }); 
}
</script>
<script type="text/javascript">
    $(function() {
        
        var $settings = $("#settings");

        var api = $("#menu").data( "mmenu" );

        //	Choose location
        var $set_location = $("#setting-location .mm-counter");
        $("#locations").find( "li span" ).click(function() {
           // $set_location.text( $(this).text() );
           // $("#selected_degree_id").val($(this).attr('vid') );
           // api.openPanel( $settings );
        });

        //	Choose radius
        var $set_radius = $("#setting-radius .mm-counter");
        $("#radius").find( "li span" ).click(function() {
            $set_radius.text( $(this).text() );
            $("#selected_category_id").val($(this).attr('vid') );
            api.openPanel( $settings );
        });

        //	Show/hide searchresults
        var $results = $(".searchresult");
        $("#locations input").keyup(function() {
            $results[ ( $(this).val() == "" ) ? "hide" : "show" ]();
        });

        //	Choose pricerange
        var $set_range = $("#setting-pricerange .mm-counter"),
            $range_from = $("#price-from"),
            $range_till = $("#price-till");

        $("#pricerange").find( ".button" ).click(function() {
            $set_range.text( $range_from.val() + " - " + $range_till.val() );
        });
    });
</script>

<nav id="menu">
    
    
    
    <!-- subpanel -->
    <div id="settings" class="Panel">
        <ul>
            
            <li id="setting-location">
                <em class="Counter" vid=''></em>
                <span>我的资料</span>

                <!-- subpanel -->
                <div id="locations" class="Panel">
                    
                    <ul>
                      
                        <li id="">
                            <em class="Counter" vid=''>{{ app('auth')->user()->username }}</em>
                            <span>用户名</span>
                        </li>
                        <li class="">
                            <em class="Counter" vid=''>{{ app('auth')->user()->email }}</em>
                            <span>邮箱</span>
                        </li>
                        
                        <li class="edit">
                            <em class="Counter"  vid=''>修改资料</em>
                            <span>&nbsp;</span>
                            <div id="editbase" class="Panel">
                                <form method="POST" id="formbase" action="/home" accept-charset="UTF-8" enctype="multipart/form-data">
                                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                        
                                <ul>
                                    <li >
                                        <em class="Counter"  id="inp-edit"><input type="text" id="inp-name" value="{{ app('auth')->user()->username }}" name="username" placeholder="username" /></em>
                                        <span>用户名</span>
                                    </li>
                                   
                                    <li >
                                        <em class="Counter"  id="inp-edit"><input type="text" id="inp-name" value="{{ app('auth')->user()->email }}" name="email" placeholder="email" /></em>
                                        <span>邮箱</span>
                                    </li>
                                    <li >
                                        <em class="Counter"  id="inp-edit" style="top:10%;"><input type="file" id="upload"  name="avatar"  /></em>
                                        <span>头像</span>
                                    </li>
                                    <li class="edit">
                                        <em class="Counter" onclick="$('#formbase').submit();" id="edit" vid=''>提交</em>
                                            <span>&nbsp;</span>
                                    </li>
                                </ul>
                                </form>
                            </div>
                        </li>
                       
                        <br>
                        <li class="">
                            <em class="Counter" vid=''>18601991350</em>
                            <span>手机号</span>
                            
                        </li>
                        <li class="edit">
                            <em class="Counter"  vid=''>修改手机号</em>
                            <span>&nbsp;</span>
                            <div id="editmobile" class="Panel">
                        
                                <ul>
                                    <li >
                                        <em class="Counter"  id="inp-edit"><input type="number" id="inp-name" value="" class="editmobile" name="mobile" placeholder="手机号" /></em>
                                        <span>手机号</span>
                                    </li>
                                   
                                    
                                    <li >
                                        <em class="Counter"  id="inp-edit" ><input type="button" id="inp-name" onclick="djs(this,$('.editmobile'))" style="width:40%; padding: 0px; margin-left: 5px;" value="获取验证码"/>&nbsp;<input type="number" id="inp-name" value="" class="editverify_code" name="verify_code" style="width:25%;" placeholder="" /></em>
                                        <span>验证码</span>
                                    </li>
                                    <li class="edit">
                                        <em class="Counter" onclick="editmobile();" id="edit" vid=''>提交</em>
                                            <span>&nbsp;</span>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <br>
                       
                        <li class="">
                            <em class="Counter" >修改</em>
                            <span>密码</span>
                            <div id="editpwd" class="Panel">
                        
                                <ul>
                                    <li >
                                        <em class="Counter"  id="inp-edit"><input type="password" id="inp-name" value="" name="userold_password" placeholder="当前密码" /></em>
                                        <span>当前密码</span>
                                    </li>
                                   
                                    <li >
                                        <em class="Counter"  id="inp-edit"><input type="password" id="inp-name" value="" name="userpassword" placeholder="新密码" /></em>
                                        <span>新密码</span>
                                    </li>
                                    <li >
                                        <em class="Counter"  id="inp-edit" ><input type="password" id="inp-name" value="" name="userpassword_confirmation" placeholder="确认新密码" /></em>
                                        <span>确认新密码</span>
                                    </li>
                                    <li class="edit">
                                        <em class="Counter" onclick="editpwd();" id="edit" vid=''>提交</em>
                                            <span>&nbsp;</span>
                                    </li>
                                </ul>
                            </div>
                        </li>
                      
                        
                    </ul>
                </div>
            </li>
            <li id="setting-radius">
                <a href="{{ route('home.messages') }}"><em class="Counter" vid=''></em>
                    <span>我的消息</span></a>

                <!-- subpanel -->
                <div id="radius" class="Panel">
                    
                </div>
            </li>
            <li id="setting-shoucang">
                <a href="{{ route('home.like_colleges') }}"><em class="Counter" vid=''></em>
                <span>我的收藏</span></a>
                <div id="radius" class="Panel"></div>
                
            </li>
            @if(app('auth')->user()->estimate!=null)
            <li id="setting-jieguo">
               <a href="javascript:document.getElementById('estimate_form').submit();"> <em class="Counter" vid=''></em>
                   <span>评估结果</span></a>
                 <form action="{{ URL::route('estimate.store') }}" method="POST" style="display: none" id="estimate_form">
                    <input type="hidden" name="estimate_id" value="{{app('auth')->user()->estimate}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
                <div id="radius" class="Panel"></div>
            </li>
            @endif
            <li id="setting-dan">
                <a href="/home/intentions"><em class="Counter" vid=''></em>
                    <span>我的意向单</span></a>
                    <div id="radius" class="Panel">
                    
                </div>
                
            </li>
            <li id="setting-tuichu">
                <a href="/auth/logout"><em class="Counter" vid=''></em>
                    <span>退出</span></a>
                <div id="radius" class="Panel"></div>
                
            </li>
            <!-- navbar info 
            <div class="Hidden" style="display:none;">
			
                <a class="Next" href="javascript:searSub();" >确定</a>
			</div>-->

        </ul>


    </div>
            
</nav>

