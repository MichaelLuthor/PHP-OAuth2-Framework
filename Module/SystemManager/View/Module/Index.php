<?php 
/* @var $this \X\Module\SystemManager\Action\Module\Index */
$vars = get_defined_vars();
$modules = $vars['modules'];
?>

<ol class="breadcrumb">
  <li><a href="<?php echo $this->createURL('index')?>">管理主页</a></li>
  <li class="active">模块管理</li>
</ol>

<table class="table table-bordered">
<thead>
<tr>
  <th>名称</th><th>描述</th><th>版本</th>
</tr>
</thead>
<tbody>
<?php foreach ( $modules as $moduleName => $module ) : ?>
<?php /* @var $module \X\Core\Module\XModule */ ?>
<?php $trClass = 'active'; ?>
<?php if ( $module->isDefaultModule() ) : ?>
  <?php $trClass = 'success'; ?>
<?php elseif ( $module->isEnabled() ) : ?>
  <?php $trClass = 'active'; ?>
<?php else : ?>
  <?php $trClass = 'danger'; ?>
<?php endif; ?>
<tr class="<?php echo $trClass; ?>">
  <td>
    <a href="<?php echo $this->createURL('module/detail', array('name'=>$moduleName)); ?>">
      <?php echo $this->encodeHtmlString($module->getPrettyName()); ?>
    </a>
  </td>
  <td><?php echo $this->encodeHtmlString($module->getDescription()); ?></td>
  <td><?php echo $this->encodeHtmlString(implode('.', $module->getVersion())); ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<a class="btn btn-default" href="<?php echo $this->createURL('module/create'); ?>">新建模块</a>
<a class="btn btn-default" href="<?php echo $this->createURL('module/import'); ?>">导入模块</a>
<br>
<br>