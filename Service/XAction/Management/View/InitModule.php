<?php 
/* @var $this \X\Service\XAction\Management\Action\InitModule */
$this->addHeaderItem('<link href="http://cdn.bootcss.com/highlight.js/9.0.0/styles/default.min.css" rel="stylesheet">');
$this->addHeaderItem('<script src="http://cdn.bootcss.com/highlight.js/9.0.0/highlight.min.js"></script>');

$vars = get_defined_vars();

$modules = $vars['modules'];
$status = $vars['status'];
$moduleName = $vars['moduleName'];
$defaultActionName = $vars['defaultActionName'];

$service = $this->getService();
?>

<ol class="breadcrumb">
  <li><a href="<?php echo $this->createURL('index')?>">管理主页</a></li>
  <li><a href="<?php echo $this->createURL('service/index'); ?>">服务管理</a></li>
  <li><a href="<?php echo $this->createURL('service/detail', array('name'=>$service->getName())); ?>"><?php echo $this->encodeHtmlString($service->getPrettyName()); ?></a></li>
  <li class="active">初始化模块</li>
</ol>

<strong>初始化<?php echo $this->encodeHtmlString($service->getPrettyName()); ?>服务到模块</strong>
<br>
<br>

<?php if ( null !== $status ): ?>
  <div class="alert alert-<?php echo $status[0]; ?>">
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    <?php echo $this->encodeHtmlString($status[1]); ?>
  </div>
  
  <?php if('success' === $status[0]): ?>
  <pre>
    <code class="php"><?php echo $status[2]; ?></code>
  </pre>
  <script>
  $(document).ready(function() {
    hljs.initHighlighting();
  });
  </script>
  <?php endif;?>
<?php endif; ?>

<?php $actionURL = $this->createURL('service/advance', array('name'=>$service->getName(), 'extaction'=>'initModule')); ?>
<form action="<?php echo $actionURL; ?>" method="post">
  <div class="form-group">
    <label>目标模块</label>
    <select class="form-control" name="moduleName">
      <option value="" <?php if(''===$moduleName): ?>selected<?php endif;?>> 未选择
      <?php foreach ( $modules as $module ) : ?>
        <?php /* @var $module \X\Module\SystemManager\Module */ ?>
        <?php $optionStatus = ($moduleName===$module->getName()) ? 'selected' : ''; ?>
        <option value="<?php echo $module->getName(); ?>" <?php echo $optionStatus;?>>
        <?php echo $this->encodeHtmlString($module->getPrettyName()); ?>
      <?php endforeach;?>
    </select>
  </div>
  
  <div class="form-group">
    <label>默认动作名称</label>
    <input type="text" name="defaultActionName" class="form-control" value="<?php echo $defaultActionName; ?>">
  </div>
  
  <button type="submit" class="btn btn-default">执行初始化</button>
</form>