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
        <a href="#">指南针官网</a>
      </li>
      <li class='nav-item'>
        @if(Auth::check())
          <a href="{{route('home.index')}}">
            <div class="user-avatar">
              <img src="{{ app('auth')->user()->getAvatarPath() }}" />
            </div>
          </a>
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
