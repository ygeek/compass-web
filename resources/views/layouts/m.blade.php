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

    <script type="text/javascript">
      Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#_token').getAttribute('value');

      new Vue({
        el: '#app',
        data: {
          showLoginAndRegisterPanel: false,
          showChangePhonePanel: false
        }
      });
    </script>
  </body>
</html>
