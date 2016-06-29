<!-- Sidebar -->
<nav id="sidebar">
  <!-- Sidebar Scroll Container -->
  <div id="sidebar-scroll">
    <!-- Sidebar Content -->
    <div class="sidebar-content">
      <!-- Side Header -->
      <div class="side-header side-content bg-white-op">
        <div class="btn-group pull-right">
          <button class="btn btn-link text-gray" data-toggle="dropdown" type="button">
            <i class="si si-bell"></i>
          </button>
        </div>

        <button class="btn btn-link text-gray pull-right hidden-md hidden-lg" type="button" data-toggle="layout" data-action="sidebar_close">
          <i class="fa fa-times"></i>
        </button>

        <span class="h4 font-w600 sidebar-mini-hide">
          {{ Auth::guard('admin')->user()->name}}
        </span>

      </div>
      <!-- END Side Header -->

      <!-- Side Content -->
      <div class="side-content">
        <ul class="nav-main">
          <li>
            <a class="" href="#">
              <i class="si si-speedometer"></i><span class="sidebar-mini-hide">
                后台管理
              </span>
            </a>
          </li>
          <li class="nav-main-heading"><span class="sidebar-mini-hide">系统管理</span></li>
          <li>
              <a href="{{ route('admin.setting.index', ['key' => 'core_range']) }}"><i class="si si-rocket"></i><span class="sidebar-mini-hide">核心冲刺概率设置</span></a>
          </li>
          <li>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#">
              <i class="si si-badge"></i><span class="sidebar-mini-hide">院校管理</span></a>
            <ul>
              <li>
                <a href="{{ route('admin.colleges.index') }}"><i class="si si-rocket"></i>
                  <span class="sidebar-mini-hide">院校列表</span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.colleges.create') }}"><i class="si si-rocket"></i>
                  <span class="sidebar-mini-hide">新增院校</span>
                </a>
              </li>
            </ul>
        </li>

          <li>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#">
              <i class="si si-badge"></i><span class="sidebar-mini-hide">分数比例设置</span></a>
            <ul>
              <li>
                <a href="{{ route('admin.examination_score_weights.index') }}"><i class="si si-rocket"></i>
                  <span class="sidebar-mini-hide">比例列表</span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.examination_score_weights.create') }}"><i class="si si-rocket"></i>
                  <span class="sidebar-mini-hide">新增规则</span>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <!-- END Side Content -->
    </div>
    <!-- Sidebar Content -->
  </div>
  <!-- END Sidebar Scroll Container -->
</nav>
<!-- END Sidebar -->