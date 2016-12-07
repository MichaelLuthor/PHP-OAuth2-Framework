<?php 
/* @var $this \X\Module\SystemManager\Action\Module\Detail */
$vars = get_defined_vars();
$error = $vars['error'];
$position = $vars['position'];
?>

<ol class="breadcrumb">
  <li><a href="<?php echo $this->createURL('index')?>">管理主页</a></li>
  <li><a href="<?php echo $this->createURL('service/index'); ?>">服务管理</a></li>
  <li class="active">上传服务</li>
</ol>

<?php if (null !== $error ): ?>
  <div class="alert alert-danger"><?php echo $this->encodeHtmlString($error); ?></div>
<?php endif; ?>

<form action="<?php echo $this->createURL('service/import'); ?>" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label>服务位置</label>
    <select class="form-control" name="position">
      <?php foreach ( $position as $positionKey => $positionName ): ?>
        <option value="<?php echo $positionKey?>"><?php echo $this->encodeHtmlString($positionName); ?>
      <?php endforeach; ?>
    </select>
  </div>
  
  <div class="form-group">
    <label>服务包</label>
    <input type="file" name="package">
  </div>
  <button type="submit" class="btn btn-default">导入</button>
</form>
