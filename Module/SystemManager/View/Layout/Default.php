<?php /* @var $this \X\Module\SystemManager\Util\Action */ ?>
<?php $vars = get_defined_vars(); ?>
<?php $content = $vars['content']; ?>
<?php $head = $vars['head']; ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
  <meta charset="utf-8">
  <link href="//cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
  <script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
  <script src="http://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <?php foreach ( $head as $headItem ) : ?>
    <?php echo $headItem; ?>
  <?php endforeach; ?>
</head>
<body>
<div class="container">
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="<?php echo $this->createURL('index'); ?>">框架管理</a>
      </div>
    
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <?php $action = isset($_GET['action']) ? explode('/', $_GET['action']) : array(null); ?>
          <?php $action = $action[0]; ?>
          <li class="<?php if('module'===$action): ?>active<?php endif; ?>">
            <a href="<?php echo $this->createURL('module/index'); ?>">模块</a>
          </li>
          <li class="<?php if('service'===$action): ?>active<?php endif; ?>">
            <a href="<?php echo $this->createURL('service/index'); ?>">服务</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  
  <?php echo $content; ?> 
</div>
</body>
</html>