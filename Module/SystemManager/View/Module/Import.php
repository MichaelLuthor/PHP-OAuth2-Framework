<?php 
/* @var $this \X\Module\SystemManager\Action\Module\Detail */
$vars = get_defined_vars();
$error = $vars['error'];
?>

<ol class="breadcrumb">
  <li><a href="<?php echo $this->createURL('index')?>">管理主页</a></li>
  <li><a href="<?php echo $this->createURL('module/index'); ?>">模块管理</a></li>
  <li class="active">上传模块</li>
</ol>

<?php if (null !== $error ): ?>
  <div class="alert alert-danger"><?php echo $this->encodeHtmlString($error); ?></div>
<?php endif; ?>

<form action="<?php echo $this->createURL('module/import'); ?>" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label>模块包</label>
    <input type="file" name="package">
  </div>
  <button type="submit" class="btn btn-default">导入</button>
</form>
