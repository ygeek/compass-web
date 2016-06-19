<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>指南针留学</title>
    <link rel="stylesheet" type="text/css" href="{{ elixir('css/app.css')}}" />
  </head>
  <body>
    @include('shared.login_panel')

    <div>
      @yield('content')
    </div>

    <script src="/js/jquery-3.0.0.min.js"></script>
  </body>
</html>
