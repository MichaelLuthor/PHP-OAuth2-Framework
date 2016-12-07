<?php 
use X\Core\Service\XService;
/* @var $this \X\Module\SystemManager\Action\Service\Detail */
$this->addHeaderItem('<link rel="stylesheet" href="Assets/SystemManager/Lib/FlexiJsonEditor/jsoneditor.css"/>');
$this->addHeaderItem('<script src="Assets/SystemManager/Lib/FlexiJsonEditor/jquery.jsoneditor.js"></script>');

$vars = get_defined_vars();
/* @var $service \X\Core\Service\XService */
$service = $vars['service'];
/* @var $manager \X\Module\SystemManager\Library\ServiceManager */
$manager = $vars['manager'];
?>

<ol class="breadcrumb">
  <li><a href="<?php echo $this->createURL('index')?>">管理主页</a></li>
  <li><a href="<?php echo $this->createURL('service/index')?>">服务管理</a></li>
  <li class="active"><?php echo $this->encodeHtmlString($service->getPrettyName()); ?></li>
</ol>

<strong><?php echo $this->encodeHtmlString($service->getPrettyName()); ?> (<?php echo implode('.', $service->getVersion()); ?>)</strong>
<?php 
switch ( $service->getStatus() ) {
case XService::STATUS_RUNNING: echo '<span class="label label-success">运行中</span>'; break;
case XService::STATUS_STOPPED: echo '<span class="label label-warning">已停止</span>'; break;
default: echo '<span class="label label-danger">异常</span>'; break;
}
?>
<br>
<small> - <?php echo get_class($service); ?></small>

<br>
<br>
<p><?php echo $this->encodeHtmlString($service->getDescription()); ?></p>
<br>

<a class="btn btn-default" href="<?php echo $this->createURL('service/document', array('name'=>$service->getName(), 'type'=>'service')); ?>">服务文档</a>
<a class="btn btn-default" href="<?php echo $this->createURL('service/document', array('name'=>$service->getName(), 'type'=>'configuration')); ?>">配置文档</a>

<hr>
<strong>配置文件</strong><br><br>
<form action="<?php echo $this->createURL('service/updateConfiguration', array('name'=>$service->getName())); ?>" method="post">
<div id="service-config-editor" class="json-editor well well-sm"></div>
<textarea class="hidden" id="service-config-content" name="service_config_content"></textarea>
<button class="btn btn-default" type="submit">保存</button>
<button class="btn btn-default" type="button" id="btn-reset">重置</button>
<script>
$(document).ready(function(){
    var configuration = <?php echo json_encode($service->getConfiguration()->toArray(), JSON_FORCE_OBJECT); ?>;
    var opt = { change: function(data) {
        $('#service-config-content').val(JSON.stringify(data));
    }};
    
    $('#service-config-content').val(JSON.stringify(configuration));
    $('#service-config-editor').jsonEditor(configuration, opt);
    $('#btn-reset').click(function() {
        window.location.reload();
    });
});
</script>
</form>

<br>
<hr>
<strong>质量控制工具</strong><br><br>
<a href="<?php echo $this->createURL('tool/phpmd', array('type'=>'service', 'target'=>$service->getName())); ?>" class="btn btn-default">代码体检</a>
<a href="<?php echo $this->createURL('tool/phpcpd', array('type'=>'service', 'target'=>$service->getName())); ?>" class="btn btn-default">重复代码检查</a>
<a href="<?php echo $this->createURL('tool/phpunit/execute', array('type'=>'service', 'target'=>$service->getName())); ?>" class="btn btn-default">单元测试</a>

<br>
<hr>
<strong>管理工具</strong><br><br>
<?php $toggleEnableURL = $this->createURL('service/toggleEnable', array('name'=>$service->getName())); ?>
<?php if ($service->isEnabled()): ?>
  <a href="<?php echo $toggleEnableURL; ?>" class="btn btn-danger" onclick="return confirm('确认禁用该服务？');">禁用服务</a>
<?php else : ?>
  <a href="<?php echo $toggleEnableURL; ?>" class="btn btn-success" onclick="return confirm('是否启用该服务？');">启用服务</a>
<?php endif; ?>

<?php $toggleLazyLoadURL = $this->createURL('service/toggleLazyLoad', array('name'=>$service->getName())); ?>
<?php if ($service->isLazyLoadEnabled()): ?>
  <a href="<?php echo $toggleLazyLoadURL; ?>" class="btn btn-danger" onclick="return confirm('确认关闭延迟加载吗？');">禁用延迟加载</a>
<?php else : ?>
  <a href="<?php echo $toggleLazyLoadURL; ?>" class="btn btn-success" onclick="return confirm('确认启用延迟加载吗？');">启用延迟加载</a>
<?php endif; ?>

<?php if (!$service->isEnabled()): ?>
  <a href="<?php echo $this->createURL('service/delete', array('name'=>$service->getName())); ?>" 
     class="btn btn-danger" 
     onclick="return confirm('确认删除该服务？');"
  >删除</a>
<?php endif; ?>

<a href="<?php echo $this->createURL('service/export', array('name'=>$service->getName())); ?>" class="btn btn-default">导出服务安装包</a>

<br>
<br>

<?php if ( null !== $manager ): ?>
  <hr>
  <strong>高级管理工具</strong><br><br>
  <?php foreach ( $manager->getExtendedManagementTools() as $action => $actionDef ) : ?>
    <?php $actionURL = $this->createURL('service/advance', array('name'=>$service->getName(), 'extaction'=>$action)); ?>
    <a href="<?php echo $actionURL; ?>" class="btn btn-default"><?php echo $this->encodeHtmlString($actionDef['name']); ?></a>
  <?php endforeach; ?>
  <br>
  <br>
<?php endif; ?>