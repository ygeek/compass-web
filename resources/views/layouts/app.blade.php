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
  <body id="app" v-bind:class="{ 'modal-opened': showLoginAndRegisterPanel }">
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


    <div id="go-top">
      @include('shared.login_panel')
      <div>
        @yield('content')
      </div>
    </div>

    <script>
    Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#_token').getAttribute('value');

    var app = new Vue({
      el: '#app',
      data: {
        showLoginAndRegisterPanel: false,
        showChangePhonePanel: false,
        showEstimatePanel: false,
        estimatePanelPath: null,
        currentUser: @if(Auth::check())
          <?php
            $user = Auth::User();
            $userInfo = $user->toArray();
            $userInfo['avatarPath'] = $user->getAvatarPath();
            echo json_encode($userInfo);
           ?>
        @else null @endif,
      },
      events: {
        'changeLikeDispatch': function (collegeid,like) {
          this.$broadcast('changeCollegeLike',collegeid,like)
        },
        'toShowLoginAndRegisterPanel': function () {
          this.showLoginAndRegisterPanel = true;
        },
        'setCurrentUser': function(user) {
          this.currentUser = user;
        }
      },
      methods: {
        'setEstimatePanel': function (path) {
          this.showEstimatePanel = true;
          this.estimatePanelPath = path;
        }
      }
    });

    </script>
  </body>
</html>
