<?php 
/* @var $this \X\Service\XAction\Management\Action\InitModule */

$vars = get_defined_vars();
$status = $vars['status'];

$service = $this->getService();
?>

<ol class="breadcrumb">
  <li><a href="<?php echo $this->createURL('index')?>">管理主页</a></li>
  <li><a href="<?php echo $this->createURL('service/index'); ?>">服务管理</a></li>
  <li><a href="<?php echo $this->createURL('service/detail', array('name'=>$service->getName())); ?>"><?php echo $this->encodeHtmlString($service->getPrettyName()); ?></a></li>
  <li class="active">导入行为列表</li>
</ol>

<?php if ( null !== $status ): ?>
  <div class="alert alert-<?php echo $status[0]; ?>">
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    <?php echo $this->encodeHtmlString($status[1]); ?>
    
    <?php if ('success' === $status[0]) : ?>
    <br>
    
    <?php foreach ( $status[2] as $createdActionName => $createdActionPath ) : ?>
      <p><strong><?php echo $createdActionName?></strong> <?php echo $createdActionPath; ?></p>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
<?php endif; ?>

<strong>导入CSV示例：</strong><br><br>
<table class="table table table-bordered">
  <tr>
    <td>module</td>
    <td>action</td>
    <td>extends</td>
    <td>params</td>
  </tr>
  <tr>
    <td>{moduleID}</td>
    <td>{action/name}</td>
    <td>{Extended\Class\Name}</td>
    <td>{param:list}</td>
  </tr>
  <tr>
    <td>Sport</td>
    <td>picture/index</td>
    <td>X\Service\XAction\Core\Handler\WebAction</td>
    <td>sport:运动ID;picture:运动ID</td>
  </tr>
</table>

<?php $actionURL = $this->createURL('service/advance', array('name'=>$service->getName(), 'extaction'=>'actionsDefImport')); ?>
<form action="<?php echo $actionURL; ?>" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label>选择导入文件：</label>
    <input type="file" name="file">
  </div>
  <button type="submit" class="btn btn-default">导入</button>
</form>