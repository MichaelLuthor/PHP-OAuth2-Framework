<?php 
/* @var $this \X\Module\SystemManager\Action\Module\Detail */
$vars = get_defined_vars();
$module = $vars['module'];
$errors = $vars['errors'];
?>

<ol class="breadcrumb">
  <li><a href="<?php echo $this->createURL('index')?>">管理主页</a></li>
  <li><a href="<?php echo $this->createURL('module/index'); ?>">模块管理</a></li>
  <li class="active">创建模块</li>
</ol>

<?php if ( isset($errors['main']) && null !== $errors['main'] ): ?>
<div class="alert alert-danger"><?php echo $errors['main']; ?></div>
<?php endif; ?>
<form action="<?php echo $this->createURL('module/create'); ?>" method="post">
  <div class="form-group">
    <label>模块ID</label>
    <input type="text" 
           class="form-control" 
           name="newModule[id]" 
           value="<?php echo $module['id']; ?>">
    <?php if( isset($errors['id'])): ?>
      <p class="text-danger"><?php echo $errors['id']; ?></p>
    <?php endif; ?>
  </div>
  
  <div class="form-group">
    <label>名称</label>
    <input type="text" 
           class="form-control" 
           name="newModule[name]" 
           value="<?php echo $module['name']; ?>">
  </div>
  
  <div class="form-group">
    <label>描述</label>
    <textarea class="form-control" name="newModule[description]"
    ><?php echo $this->encodeHtmlString($module['description']); ?></textarea>
  </div>
  
  <div class="form-group">
    <label>继承于</label>
    <input type="text" 
           class="form-control" 
           placeholder="该模块类所继承的类名。" 
           name="newModule[extends]" 
           value="<?php echo $module['extends']; ?>">
    <?php if( isset($errors['extends'])): ?>
      <p class="text-danger"><?php echo $errors['extends']; ?></p>
    <?php endif; ?>
  </div>
  
  <div class="form-group">
    <label>其他方法</label>
    <textarea class="form-control" name="newModule[otherMethods]" rows="10"
    ><?php echo $this->encodeHtmlString($module['otherMethods']); ?></textarea>
  </div>
  
  <div class="form-group">
    <label>使用空格代替TAB</label> &nbsp;&nbsp;&nbsp;
    <input type="hidden" name="newModule[useSpace]" value="no">
    <input type="checkbox" name="newModule[useSpace]" value="yes" <?php if ('yes'===$module['useSpace']): ?>checked<?php endif;?>>
  </div>
  
  <div class="form-group">
    <label>TAB尺寸</label>
    <input type="text" 
           class="form-control" 
           name="newModule[tabSize]" 
           value="<?php echo $module['tabSize']; ?>">
  </div>
  
  <button type="submit" class="btn btn-default">创建</button>
</form>