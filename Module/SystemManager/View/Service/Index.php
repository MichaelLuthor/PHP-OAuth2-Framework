<?php 
/* @var $this \X\Module\SystemManager\Action\Service\Index */
$vars = get_defined_vars();
$services = $vars['services'];
?>

<ol class="breadcrumb">
  <li><a href="<?php echo $this->createURL('index')?>">管理主页</a></li>
  <li class="active">服务管理</li>
</ol>

<table class="table table-bordered">
<thead>
<tr>
  <th>名称</th><th>描述</th><th>版本</th>
</tr>
</thead>
<tbody>
<?php foreach ( $services as $serviceName => $service ) : ?>
<?php /* @var $service \X\Core\Service\XService */ ?>
<?php $trClass = 'active'; ?>
<?php if ( $service->isEnabled() ) : ?>
  <?php $trClass = 'active'; ?>
<?php else : ?>
  <?php $trClass = 'danger'; ?>
<?php endif; ?>
<tr class="<?php echo $trClass; ?>">
  <td>
    <a href="<?php echo $this->createURL('service/detail', array('name'=>$serviceName)); ?>">
      <?php echo $this->encodeHtmlString($service->getPrettyName()); ?>
    </a>
  </td>
  <td><?php echo $this->encodeHtmlString($service->getDescription()); ?></td>
  <td><?php echo $this->encodeHtmlString(implode('.', $service->getVersion())); ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<a class="btn btn-default" href="<?php echo $this->createURL('service/create'); ?>">新建服务</a>
<a class="btn btn-default" href="<?php echo $this->createURL('service/import'); ?>">导入服务</a>