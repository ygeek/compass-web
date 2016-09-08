<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="renderer" content="webkit">
    <title>指南针留学</title>
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/app.css')}}" />
    <meta id="_token" value="{{ csrf_token() }}">
    <script src="/js/vue.js"></script>
    <script src="/js/vue-resource.min.js"></script>
  </head>
  <body id="go-top">
  @if(!(isset($cpm) && $cpm))
  <a href="#go-top"><img src="/images/top.gif" class="top" alt="top"/></a>
  @endif

    @if (Session::has('flash_notification.message'))
      <div class="shanbox alert-{{ Session::get('flash_notification.level') }}">
        {{ Session::get('flash_notification.message') }}
      </div>
    @endif

    @if (count($errors) > 0)
      <div class="shanbox alert-{{ Session::get('flash_notification.level') }}">
        @foreach ($errors->all() as $error)
          {{ $error }}
        @endforeach
      </div>
    @endif


    <div id="app">
      @unless(Auth::check())
        @include('shared.login_panel')
      @endunless
      <div>
        @yield('content')
      </div>
    </div>
    <script src="/js/app.js"></script>
  </body>
</html>
