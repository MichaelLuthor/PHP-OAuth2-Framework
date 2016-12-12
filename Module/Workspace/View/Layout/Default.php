<?php 
/** @var $this \X\Service\XView\Core\Handler\Html */
$this->title = "Workspace";
$this->getMetaManager()->setCharset('UTF-8');

$vars = get_defined_vars();

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

$title = $vars['title'];
$menu = $vars['menu'];
$activeMenuItem = $vars['activeMenuItem'];
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
      <ul class="sidebar-menu">
      <?php foreach ( $menu as $menuGroup ) : ?>
        <li class="header"><?php echo $menuGroup['title']; ?></li>
        <?php foreach ( $menuGroup['menu'] as $menuItemKey => $menuItem ) :?>
          <?php if (isset($menuItem['subMenu'])): ?>
          <li class="treeview <?php if($activeMenuItem['main']===$menuItemKey):?>active<?php endif;?>">
            <a href="#">
              <i class="fa fa-flask"></i>
              <span><?php echo $menuItem['title']; ?></span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
            <?php foreach ( $menuItem['subMenu'] as $subMenuItemKey => $subMenuItem ) : ?>
              <li class="<?php if($activeMenuItem['sub']===$subMenuItemKey):?>active<?php endif;?>">
                <?php $subMenuItem['link'] = isset($subMenuItem['link']) ? $subMenuItem['link'] : '#';?>
                <a href="<?php echo $subMenuItem['link']; ?>"><i class="fa fa-circle-o"></i><?php echo $subMenuItem['title']; ?></a>
              </li>
            <?php endforeach;?>
            </ul>
          </li>
          <?php else :?>
          <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span><?php echo $menuItem['title']; ?></span></a></li>
          <?php endif;?>
        <?php endforeach; ?>
      <?php endforeach; ?>
      </ul>
    </section>
  </aside>

  <div class="content-wrapper">
    <section class="content-header">
      <h1><?php echo $title; ?></h1>
    </section>

    <section class="content">
    <?php 
    $particleManager = $this->getParticleViewManager();
    foreach ( $particleManager->getList() as $particleName ) {
        $particleManager->get($particleName)->display();
    }
    ?>
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