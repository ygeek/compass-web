<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Cache-Control" content="no-cache" />
        <meta name="viewport" content="width=device-width"/>
        <meta name="MobileOptimized" content="320"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta content="initial-scale=1.0,user-scalable=no,maximum-scale=1" media="(device-height: 568px)" name="viewport" />
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta content="telephone=no" name="format-detection" />
        <meta name="_token" content="{{ csrf_token() }}"/>
        <title>指南针留学</title>
        <link type="text/css" href="/static/css/main.css" rel="stylesheet" />

        <script type="text/javascript" src="/static/js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript" src="/static/js/modernizr.custom.js"></script>
        <script type="text/javascript" src="/static/js/jquery.dlmenu.js"></script>
        <script src="/js/vue.js"></script>
        <script src="/js/vue-resource.min.js"></script>

        <script type="text/javascript" src="/static/layer_mobile/modernizr.js"></script>
         <link type="text/css" href="/static/css/common.css" rel="stylesheet" />
    </head>
<body>

<div class="header" id="header">
    <a href="#"><div class="header_logo"><img src="/static/images/logo.png" /></div></a>
    <div class="header_r">
        <div id="dl-menu" class="dl-menuwrapper">
            <button id="dl-menu-button">Open Menu</button>
            <ul class="dl-menu">
                <li><a href="/index.php">首页</a></li>
                <li><a href="/estimate/step-1">免费留学评估</a></li>
                <li><a href="/colleges">院校查询</a></li>
                @if(Auth::check())
                <li class="headuser"><a href="{{route('home.index')}}"  >个人中心</a></li>
                <li class="headuser"><a href="{{route('auth.logout_user')}}"  >退出</a></li>
                @else
                 <li class="headlogin"><a href="javascript:changeView('#login')"  >登录</a></li>
                <li  class="headlogin"><a href="javascript:changeView('#region')">注册</a></li>
                @endif

                <!--
                <li>
                    <a href="Line">自由行</a>
                    <ul class="dl-submenu">
                        <li class="dl-back"><a href="#">返回上一级</a></li>
                        <li><a href="http://www.777moban.com/">线路</a></li>
                        <li><a href="http://www.777moban.com/">签证</a></li>
                        <li><a href="http://www.777moban.com/">门票</a></li>
                    </ul>
                </li>
                -->
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $('#dl-menu').dlmenu();

    });
</script>


<div id="login" islocal='1' class="pt-login" >
    <div class="header">
        <a href="javascript:goBlack('#login')"><div class="header_l"><img src="/static/images/back.png" height="20" /></div></a>
        <div class="header_c">登录</div>
        <div class="header_r"></div>
    </div>
    <div class="clear"></div>
    <div class="main">
        <div class="login_resgister">
            <form action="" method="post" >
                <input type="number" class="login_resgister_input" placeholder="手机号码" name="phone_number">
                <input type="password" class="login_resgister_input" placeholder="密码" name='password'>
                <div class="login_mima resgister_xy">
                    <a href="javascript:changeView('#region')">注册</a>
                </div>
                <input type="button"  value="登录" class="login_button toLogin">
            </form>
        </div>
        <div class="clear"></div>
    </div>
</div>

<div id="region" islocal='1' class="pt-region" >
    <div class="header">
        <a href="javascript:goBlack('#region')"><div class="header_l"><img src="/static/images/back.png" height="20" /></div></a>
        <div class="header_c" >注册</div>
        <div class="header_r"></div>
    </div>Ï
    <div class="clear"></div>
    <div class="main">
        <div class="login_resgister" style="padding-top: 15px;">
            <form action="" method="get">
                <div class="yanzhenma" style=" background: none;">
                <select v-model="phone_country" name="phone_country" style="width: 27%;float: left; height: 50px; line-height: 50px; background-color: #fff; border: none;">
                    <option value="china">中国</option>
                    <option value="aus">澳洲</option>
                    <option value="nzl">新西兰</option>
                </select>
                    <input type="number" class="login_resgister_input zcphone_number" placeholder="手机号码" v-model="phone_number" name="zcphone_number" style="width: 70%; float: right;" >
                </div>
                <input type="password" class="login_resgister_input" placeholder="密码" v-model="password" name="zcpassword">
                <!--
                <div class="yanzhenma">
                    <input type="text" class="yzm01" placeholder="验证码" v-model="verify_code" name="verify_code">
                    <span><img src="/static/images/yzm.jpg" height="50"></span>
                    <em><a href="#">换一张</a></em>
                </div>
                -->
                <div class="clear"></div>
                <div class="yanzhenma01">
                    <input type="text" class="yzm01" placeholder="短信验证" v-model="verify_code" name="verify_code">
                    <em><input type="button" style="border: none; background-color: #fff; color: #999; margin-top: -10px;"  class="getVerify" onclick="djs(this,$('.zcphone_number'))" value="获取验证码"></em>
                </div>
                <div class="clear"></div>
                <div class="resgister_xy"><a href="javascript:changeView('#xieyi')" style="float: left; ">注册即同意<font style="color: #005eac;" >《指南针用户协议》</font></a><a href="javascript:changeView('#login')" style=" float: right;">登录</a></div>
                <input type="button" value="注册" class="login_button toRegion">
            </form>
        </div>
        <div class="clear"></div>
    </div>
</div>
<div id="xieyi" islocal='1' class="pt-xieyi" style="display: none;" >
    <div class="header">
        <div class="header_l">&nbsp;</div>
        <div class="header_c" >指南针用户协议</div>
        <div class="header_r" style="margin: 20px 0 0 0; cursor: pointer;" onclick='showxieyi("#xieyi")'><img src="/static/images/guanbi.png" height="20" /></div>
    </div>Ï
    <div class="clear"></div>
    <div class="main">
        @include('layouts.agreement')
        
    </div>
</div>
<div id="content" >
<?php
    function objToArr($obj)
    {
        if(!is_array($obj)&&!is_object($obj)) return $obj;
        if(is_object($obj)) $obj = $obj->toArray();
        foreach ($obj as $key=>$val)
        {
            $obj[$key] = objToArr($val);

        }
        return $obj;
    }
?>
<script>
var countdown=60;
function djs(obj,objmobile)
{
    var mobile = objmobile.val();

    if(validatemobile(mobile))
    {
        settime(obj);
        $.ajax({
            type:'POST',
            url:'/auth/verify-codes',
            data:'phone_number='+mobile,
            async:false,
            headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            dataType:'json',
            success:function(e){
                if(e.status == 'ok'){
                    //$("input[name='verify_code']").val(e.data.code);
                }

            }
        });
    };

}
function showxieyi(newView) {
    $("#content").hide();
    
    $("#header").hide();
    $("#region").show();
    $("#login").hide();
    
    $(newView).hide();
}
function settime(obj) {

    if (countdown == 0) {
        obj.removeAttribute("disabled");
        obj.value="获取验证码";
        countdown = 60;
        return;
    } else {
        obj.setAttribute("disabled", true);
        obj.value="重新发送(" + countdown + ")";
        countdown--;
    }
    setTimeout(function() {  settime(obj) },1000)
}

</script>
