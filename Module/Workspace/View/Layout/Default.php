<?php 
/** @var $this \X\Service\XView\Core\Handler\Html */
$this->title = "Workspace";
$this->getMetaManager()->setCharset('UTF-8');

$link = $this->getLinkManager();
$link->addCSS('bootstrap', '/assets/library/bootstrap/css/bootstrap.min.css');
$link->addCSS('admin-lte', '/assets/library/admin-lte/css/AdminLTE.min.css');
$link->addCSS('admin-lte-skin', '/assets/library/admin-lte/css/skins/_all-skins.min.css');
$link->addCSS('font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css');
$link->addCSS('ionicons', '//cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css');

$script = $this->getScriptManager();
$script->add('jquery')->setSource('/assets/library/jquery/jquery-2.2.3.min.js');
$script->add('jquery-ui')->setSource('/assets/library/jquery-ui/jquery-ui.min.js');
$script->add('bootstrap')->setSource('/assets/library/bootstrap/js/bootstrap.min.js');
$script->add('app')->setSource('/assets/library/admin-lte/js/app.js');
$script->add('dashboard')->setSource('/assets/library/admin-lte/js/pages/dashboard.js');
$script->add('demo')->setSource('/assets/library/admin-lte/js/demo.js');
$script->add('init')->setContent("$.widget.bridge('uibutton', $.ui.button);");
?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <a href="javascript:;" class="logo">
      <span class="logo-mini"><b>A</b>LT</span>
      <span class="logo-lg"><b>Admin</b>LTE</span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs">Michael Luthor</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-footer">
                <div class="pull-right">
                  <a href="index.php?module=Workspace&action=logout" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  
  <aside class="main-sidebar">
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">Main</li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-credit-card"></i> <span>Clients</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="active"><a href="index.html"><i class="fa fa-circle-o"></i>Management</a></li>
            <li><a href="index2.html"><i class="fa fa-circle-o"></i>New Client</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-flask"></i>
            <span>APIs</span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i>Management</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i>New API</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa  fa-map-signs"></i>
            <span>Test</span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i>Authoriza</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i>API</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-book"></i>
            <span>Document</span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i>Online</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i>Export</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-th"></i>
            <span>SDK</span>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i>PHP</a></li>
            <li><a href="pages/layout/top-nav.html"><i class="fa fa-circle-o"></i>JavaScript</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i>Python</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i>Objective-C</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i>C++</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i>C</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i>Swift</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i>Go</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i>Ruby</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i>JAVA</a></li>
          </ul>
        </li>
        
        <li class="header">Configuration</li>
        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>OAuth</span></a></li>
      </ul>
    </section>
  </aside>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Dashboard<small>Control panel</small></h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <section class="content">
    CONTAINE
    </section>
  </div>
  
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 0.0.0
    </div>
    <strong>Homepage : <a href="http://almsaeedstudio.com">http://almsaeedstudio.com</a></strong>
  </footer>
</div>
</body>