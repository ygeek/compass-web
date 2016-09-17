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
                <li><a href="/estimate/step-1">留学评估</a></li>
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

    
<div id="login" class="pt-login" >
    <div class="header">
        <a href="javascript:goBlack('#login')"><div class="header_l"><img src="/static/images/back.png" height="20" /></div></a>
        <div class="header_c">登录</div>
        <div class="header_r"></div>
    </div>
    <div class="clear"></div>
    <div class="main">
        <div class="login_resgister">
            <form action="" method="post" >
                <input type="text" class="login_resgister_input" placeholder="手机号码" name="phone_number">
                <input type="password" class="login_resgister_input" placeholder="密码" name='password'>
                <div class="login_mima">
                    <a href="javascript:changeView('#region')">注册</a>
                    <a href="#" style="float:right;">忘记密码？</a>
                </div>
                <input type="button"  value="登录" class="login_button toLogin">
            </form>
        </div>
        <div class="clear"></div>
    </div>
</div>

<div id="region" class="pt-region" >
    <div class="header">
        <a href="javascript:goBlack('#region')"><div class="header_l"><img src="/static/images/back.png" height="20" /></div></a>
        <div class="header_c" >注册</div>
        <div class="header_r"></div>
    </div>Ï
    <div class="clear"></div>
    <div class="main">
        <div class="login_resgister">
            <form action="" method="get">
                <input type="text" class="login_resgister_input" placeholder="手机号码" v-model="phone_number" name="zcphone_number">
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
                    <em><a href="javascript:void(0)" class="getVerify">获取验证码</a></em>
                </div>
                <div class="clear"></div>
                <div class="resgister_xy"><a href="#">注册即同意《指南针用户协议》</a></div>
                <input type="button" value="注册" class="login_button toRegion">
            </form>
        </div>
        <div class="clear"></div>
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