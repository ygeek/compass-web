<?php
  $page = isset($page) ? $page : null; 
?>
<div class="top-nav-bar">
  <div class='top-bar-logo'>
    <a class='logo-nav-bar' href="#">
      <img src="/images/logo_ico.png" alt="logo" />
    </a>
  </div>
  <div class="top-bar-content">
    <ul class='title-nav-bar'>
      <li class="nav-item @if($page == 'index') active @endif">
        <a href="/">首页</a>
      </li>
      <li class="nav-item @if($page == 'estimate') active @endif">
        <a href="{{ route('estimate.step_first') }}">免费留学评估</a>
      </li>
      <li class="nav-item @if($page == 'colleges') active @endif">
        <a href="{{ route('colleges.index') }}">院校查询</a>
      </li>
      <li class="nav-item">
        <a href="javascript:void(0)" onclick='easemobim.bind({tenantId: 21250})'>在线留学顾问</a>
      </li>
      <li class='nav-item'>
        <a href="http://www.zhinanzhen.org" target="_blank">指南针官网</a>
      </li>
      <li class='nav-item'>
        @if(Auth::check())
          <a href="{{route('home.index')}}" id="user_avatar">
            <div class="user-avatar">
              <img src="{{ app('auth')->user()->getAvatarPath() }}"/>
            </div>
          </a>
          <div id="hidden-box" class="hidden-box">
            <ul>
              <li><a href="{{route('home.index')}}">我的资料</a></li>
              <li><a href="{{ route('home.messages') }}">我的消息</a></li>
              <li><a href="{{ route('home.like_colleges') }}">我的收藏</a></li>
              <li><a href="{{ route('home.intentions') }}">我的意向单</a></li>
            </ul>
          </div>
          <script src="/js/jquery-3.0.0.min.js"></script>
          <script>
            $('#user_avatar').hover(function(){
              $('#hidden-box').slideDown(300);
            },function(){
              $('#hidden-box').hide();
            });
            $('#hidden-box').hover(function(){
              $(this).show();
            },function(){
              $(this).slideUp(200);
            });
          </script>
        @else
          <span v-on:click="showLoginAndRegisterPanel=true">登录</span>
        @endif
      </li>
    </ul>
  </div>
</div>
<script>
window.easemobim = window.easemobim || {};
easemobim.config = {
    hide: true,   //是否隐藏小的悬浮按钮
    autoConnect: true    //自动连接
};
</script>
<script src='//kefu.easemob.com/webim/easemob.js?tenantId=21250' async='async'></script>
