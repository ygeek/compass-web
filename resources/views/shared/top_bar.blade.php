<?php
  $page = isset($page) ? $page : null;
?>

<script>
  Vue.component('user-item', {
    template: '#user-item',
    props: ['user', 'showLoginAndRegisterPanel'],
    data: function() {
      return {
        active: false
      }
    },
    methods: {
      dispatchShowLoginPanel: function(){
        this.$dispatch('toShowLoginAndRegisterPanel');
      },
      mouseOver: function() {
        this.active = true;
      },
      mouseOut: function(){
        this.active = false;
      }
    }
  })
</script>

<template id="user-item">
  <template v-if="user">
    <div>
      <a href="{{route('home.index')}}" id="user_avatar" @mouseenter="mouseOver">
        <div class="user-avatar">
          <img :src="user.avatarPath"/>
        </div>
      </a>
      <div id="hidden-box" class="hidden-box" v-show="active" @mouseleave="mouseOut">
        <ul>
          <li><a href="{{route('home.index')}}">我的资料</a></li>
          <li><a href="{{ route('home.messages') }}">我的消息</a></li>
          <li><a href="{{ route('home.like_colleges') }}">我的收藏</a></li>
            <template v-if="user.estimate">
              <form action="{{ URL::route('estimate.store') }}" method="POST" style="display: none" id="estimate_form">
                  <input type="hidden" name="estimate_id" :value="user.estimate">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
              </form>
              <li><a href="javascript:document.getElementById('estimate_form').submit();">评估结果</a></li>
            </template>
           <li><a href="{{ route('home.intentions') }}">我的意向单</a></li>
          <li><a href="{{ route('auth.logout_user') }}">退出</a></li>
        </ul>
      </div>

    </div>


  </template>

  <template v-else>
    <span @click="dispatchShowLoginPanel">登录</span>
  </template>
</template>

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
      <li class='nav-item'>
        <a href="http://www.zhinanzhen.org" target="_blank">指南针官网</a>
      </li>
      <li class='nav-item'>
        <user-item :user.sync="currentUser"></user-item>
      </li>
    </ul>
  </div>
    <div class="counselor"><a href="javascript:void(0)" onclick='easemobim.bind({tenantId: 21250})'><img src="/images/counselor.jpg" /></a></div>
</div>
<script>
window.easemobim = window.easemobim || {};
easemobim.config = {
    hide: true,   //是否隐藏小的悬浮按钮
    autoConnect: true    //自动连接
};
</script>
<script src='//kefu.easemob.com/webim/easemob.js?tenantId=21250' async='async'></script>
