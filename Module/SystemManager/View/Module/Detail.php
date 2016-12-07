<?php 
/* @var $this \X\Module\SystemManager\Action\Module\Detail */
$this->addHeaderItem('<link rel="stylesheet" href="Assets/SystemManager/Lib/FlexiJsonEditor/jsoneditor.css"/>');
$this->addHeaderItem('<script src="Assets/SystemManager/Lib/FlexiJsonEditor/jquery.jsoneditor.js"></script>');

$vars = get_defined_vars();
/* @var $module \X\Core\Module\XModule */
$module = $vars['module'];
?>

<ol class="breadcrumb">
  <li><a href="<?php echo $this->createURL('index')?>">管理主页</a></li>
  <li><a href="<?php echo $this->createURL('module/index'); ?>">模块管理</a></li>
  <li class="active"><?php echo $this->encodeHtmlString($module->getPrettyName()); ?></li>
</ol>

<strong><?php echo $this->encodeHtmlString($module->getPrettyName()); ?> (<?php echo implode('.', $module->getVersion()); ?>)</strong>

<br>
<br>
<p><?php echo $this->encodeHtmlString($module->getDescription()); ?></p>
<br>

<hr>
<strong>资源文件</strong><br>
<br>
发行时间:
<?php $assetsPublishTime = $module->getAssetsLastPublishTime(); ?>
<?php if ( null === $assetsPublishTime ) : ?>
  未发行
<?php else: ?>
  <?php echo date('Y-m-d H:i:s', $assetsPublishTime); ?>
<?php endif; ?>
<br><br>
<a 
  href="<?php echo $this->createURL('module/assetsPublish', array('name'=>$module->getName())); ?>" 
  class="btn btn-default"
  onclick="return confirm('确定重新发行资源文件吗？');"
>重新发行</a>
<a 
  href="<?php echo $this->createURL('module/assetsClean', array('name'=>$module->getName())); ?>"
  class="btn btn-danger"
  onclick="return confirm('确定清理资源文件吗？');"
>清理</a>

<hr>
<strong>配置文件</strong><br><br>
<form action="<?php echo $this->createURL('module/updateConfiguration', array('name'=>$module->getName())); ?>" method="post">
<div id="module-config-editor" class="json-editor well well-sm"></div>
<textarea class="hidden" id="module-config-content" name="module_config_content"></textarea>
<button class="btn btn-default" type="submit">保存</button>
<button class="btn btn-default" type="button" id="btn-reset">重置</button>
<script>
$(document).ready(function(){
    var configuration = <?php echo json_encode($module->getConfiguration()->toArray(), JSON_FORCE_OBJECT); ?>;
    var opt = { change: function(data) {
        $('#module-config-content').val(JSON.stringify(data));
    }};
    
    $('#module-config-content').val(JSON.stringify(configuration));
    $('#module-config-editor').jsonEditor(configuration, opt);
    $('#btn-reset').click(function() {
        window.location.reload();
    });
});
</script>
</form>

<br>
<hr>
<strong>质量控制工具</strong><br><br>
<a href="<?php echo $this->createURL('tool/phpmd', array('type'=>'module', 'target'=>$module->getName())); ?>" class="btn btn-default">代码体检</a>
<a href="<?php echo $this->createURL('tool/phpcpd', array('type'=>'module', 'target'=>$module->getName())); ?>" class="btn btn-default">重复代码检查</a>
<a href="<?php echo $this->createURL('tool/phpunit/execute', array('type'=>'module', 'target'=>$module->getName())); ?>" class="btn btn-default">单元测试</a>
<a href="<?php echo $this->createURL('tool/test/setup', array('type'=>'module', 'target'=>$module->getName())); ?>" class="btn btn-default">更新测试环境</a>

<br>
<hr>
<strong>管理工具</strong><br><br>
<?php $toggleDefaultURL = $this->createURL('module/toggleDefault', array('name'=>$module->getName())); ?>
<?php if ( $module->isDefaultModule() ): ?>
  <a href="<?php echo $toggleDefaultURL; ?>" class="btn btn-danger" onclick="return confirm('是否取消将该模块设为默认模块？');">取消默认</a>
<?php else: ?>
  <a href="<?php echo $toggleDefaultURL; ?>" class="btn btn-success" onclick="return confirm('是否将该模块设为默认模块？');">设置为默认</a>
<?php endif; ?>

<?php $toggleEnableURL = $this->createURL('module/toggleEnable', array('name'=>$module->getName())); ?>
<?php if ($module->isEnabled()): ?>
  <a href="<?php echo $toggleEnableURL; ?>" class="btn btn-danger" onclick="return confirm('确认禁用该模块？');">禁用</a>
<?php else : ?>
  <a href="<?php echo $toggleEnableURL; ?>" class="btn btn-success" onclick="return confirm('是否启用该模块？');">启用</a>
<?php endif; ?>

<?php if (!$module->isEnabled()): ?>
  <a href="<?php echo $this->createURL('module/delete', array('name'=>$module->getName())); ?>" 
     class="btn btn-danger" 
     onclick="return confirm('确认删除该模块？');"
  >删除</a>
<?php endif; ?>

<a href="<?php echo $this->createURL('module/export', array('name'=>$module->getName())); ?>" class="btn btn-default">导出模块安装包</a>

<?php if ( $module->isEnabled() ): ?>
  <a href="index.php?module=<?php echo $module->getName(); ?>" class="btn btn-default" target="_blank">运行模块</a>
<?php endif; ?>
<br>