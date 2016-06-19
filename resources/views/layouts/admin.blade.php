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
  <script src="/js/jquery-3.0.0.min.js"></script>
  <script src="/dash-assets/js/oneui.min.js"></script>
</head>
<body>
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
</body>
</html>
