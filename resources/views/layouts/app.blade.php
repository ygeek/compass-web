<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>指南针留学</title>
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/app.css')}}" />
    <meta id="_token" value="{{ csrf_token() }}"> 
    <script src="/js/vue.js"></script>
    <script src="/js/vue-resource.min.js"></script>
  </head>
  <body>
    <div id="app">
      @include('shared.login_panel')
      <div>
        @yield('content')
      </div>
    </div>
    <script src="/js/app.js"></script>
  </body>
</html>
