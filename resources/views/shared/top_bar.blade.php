<div class="top-nav-bar">
  <div class='top-bar-logo'>
    <a class='logo-nav-bar' href="#">
      <img src="/images/logo_ico.png" alt="logo" />
    </a>
  </div>
  <div class="top-bar-content">
    <ul class='title-nav-bar'>
      <li class="nav-item">
        <a href="/">首页</a>
      </li>
      <li class="nav-item">
        <a href="{{ route('estimate.step_first') }}">免费留学评估</a>
      </li>
      <li class="nav-item">
        <a href="#">院校查询</a>
      </li>
      <li class="nav-item">
        <a href="#">在线留学顾问</a>
      </li>
      <li class='nav-item'>
        <a href="#">指南针官网</a>
      </li>
      <li class='nav-item'>
        @if(Auth::check())
          <a href="{{route('home.index')}}"><span>你已登陆</span></a>
        @else
          <span v-on:click="showLoginAndRegisterPanel=true">登录</span>
        @endif
      </li>
    </ul>
  </div>
</div>
