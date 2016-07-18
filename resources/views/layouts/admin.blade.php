<!--[if IE 9]>         <html class="ie9 no-focus"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-focus"> <!--<![endif]-->
<head>
  <meta charset="UTF-8">
  <title>@yield('title') - 后台管理</title>
  <meta name="author" content="n-studio">
  <meta name="robots" content="noindex, nofollow">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
  <link rel="stylesheet" type="text/css" href="/dash-assets/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="/dash-assets/css/oneui.min.css" />
  <link rel="stylesheet" type="text/css" href="/dash-assets/bootstrap-datepicker/css/bootstrap-datepicker.min.css" />
  <meta id="_token" value="{{ csrf_token() }}">
  <script src="/js/jquery-3.0.0.min.js"></script>
  <script src="/dash-assets/js/oneui.min.js"></script>
  <script src="/js/vue.js"></script>
  <script src="/js/vue-resource.min.js"></script>
  <script src="/dash-assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
  <style type="text/css">
  .red{
    border: solid red 1px;
  }
    .modal{
      background: rgba(0,0,0,0.5);
      width: 100%;
      height: 100%;
    }

    .modal .modal-content{
      height: 500px;
    }

    .modal .modal-content .block-content{
      height: 400px;
      overflow: scroll;
    }

    .shanbox{
      width:500px;
      height:40px;
      background-color:#666;
      border-radius:5px;
      line-height:40px;
      text-align:center;
      position:fixed;
      top:50%;
      left:50%;
      margin-left:-250px;
      margin-top:-20px;
      z-index:999999;
      font-size:14px;
      font-weight:bold;
      color:#FFF;}


    @-webkit-keyframes fadeOut {
      0% { opacity: 1; }
      50% {opacity: 1;}
      100% { opacity: 0; }
    }
    @-moz-keyframes fadeOut {
      0% { opacity: 1; }
      50% {opacity: 1;}
      100% { opacity: 0; }
    }
    @-o-keyframes fadeOut {
      0% { opacity: 1; }
      50% {opacity: 1;}
      100% { opacity: 0; }
    }
    @keyframes fadeOut {
      0% { opacity: 1; }
      50% {opacity: 1;}
      100% { opacity: 0; }
    }

    .shanbox{
      -webkit-animation-name:fadeOut;
      -moz-animation-name:fadeOut;
      -o-animation-name:fadeOut;
      animation-name:fadeOut;

      -webkit-animation-duration: 1s;
      -webkit-animation-timing-function: ease;
      -webkit-animation-fill-mode: both;
      -moz-animation-duration: 1s;
      -moz-animation-timing-function: ease;
      -moz-animation-fill-mode: both;
      -ms-animation-duration: 1s;
      -ms-animation-timing-function: ease;
      -ms-animation-fill-mode: both;
      animation-duration: 1s;
      animation-timing-function: ease;
      animation-fill-mode: both;
    }
  </style>
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
    <div id="page-container" class="sidebar-l sidebar-o side-scroll">

      @include('shared.admin.sidebar')

      <!-- Header -->
      <header id="header-navbar" class="content-mini content-mini-full">

        <!-- Header Navigation Left -->
        <ul class="nav-header pull-left">
          <li class="hidden-md hidden-lg">
            <button class="btn btn-default" data-toggle="layout" data-action="sidebar_toggle" type="button">
              <i class="fa fa-navicon"></i>
            </button>
          </li>
          <li class="hidden-xs hidden-sm">
            <button class="btn btn-default" data-toggle="layout" data-action="sidebar_mini_toggle" type="button">
              <i class="fa fa-ellipsis-v"></i>
            </button>
          </li>
        </ul>
        <!-- END Header Navigation Left -->
      </header>
      <!-- END Header -->

      <!-- Main Container -->
      <main id="main-container">
        <!-- Page Header -->
        <div class="content  content-boxed bg-gray-lighter">
          <div class="row items-push">
            <div class="col-xs-7">
              @yield('header_left')
            </div>
            <div class="col-xs-5 text-right">
              @yield('header_right')
            </div>
          </div>
        </div>
        <!-- END Page Header -->

        <!-- Page Content -->
        <div class="content">
          @yield('content')
        </div>
        <!-- END Page Content -->
      </main>
      <!-- END Main Container -->

      <!-- Footer -->
      <footer id="page-footer" class="content-mini content-mini-full font-s12 bg-gray-lighter clearfix">
        <div class="pull-right">
            Crafted with <i class="fa fa-heart text-city"></i> by <a class="font-w600" href="http://goo.gl/vNS3I" target="_blank">N studio</a>
        </div>
      </footer>
      <!-- END Footer -->
    </div>
  </div>
  <script>
  Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#_token').getAttribute('value');

  new Vue({
    el: '#app',
    data: {
    }
  });

  function guid() {
    function s4() {
      return Math.floor((1 + Math.random()) * 0x10000)
        .toString(16)
        .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
      s4() + '-' + s4() + s4() + s4();
  }
  </script>
</body>
</html>
