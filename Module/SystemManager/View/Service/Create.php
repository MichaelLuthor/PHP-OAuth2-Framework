<?php 
/* @var $this \X\Module\SystemManager\Action\Module\Detail */
$vars = get_defined_vars();
$service = $vars['service'];
$errors = $vars['errors'];
$servicePosition = $vars['servicePosition'];
?>

<ol class="breadcrumb">
  <li><a href="<?php echo $this->createURL('index')?>">管理主页</a></li>
  <li><a href="<?php echo $this->createURL('service/index'); ?>">服务管理</a></li>
  <li class="active">创建服务</li>
</ol>

<?php if ( isset($errors['main']) && null !== $errors['main'] ): ?>
<div class="alert alert-danger"><?php echo $errors['main']; ?></div>
<?php endif; ?>
<form action="<?php echo $this->createURL('service/create'); ?>" method="post">
  <div class="form-group">
    <label>服务ID</label>
    <input type="text" 
           class="form-control" 
           name="newService[id]" 
           value="<?php echo $service['id']; ?>">
    <?php if( isset($errors['id'])): ?>
      <p class="text-danger"><?php echo $errors['id']; ?></p>
    <?php endif; ?>
  </div>
  
  <div class="form-group">
    <label>服务位置</label>
    <select class="form-control" name="newService[position]">
      <?php foreach ( $servicePosition as $positionKey => $positionName ): ?>
        <?php $optionStatus = ($positionKey===$service['position']) ? 'selected' : ''; ?>
        <option value="<?php echo $positionKey?>" <?php echo $optionStatus; ?>><?php echo $this->encodeHtmlString($positionName); ?>
      <?php endforeach; ?>
    </select>
    <?php if( isset($errors['position'])): ?>
      <p class="text-danger"><?php echo $errors['position']; ?></p>
    <?php endif; ?>
  </div>
  
  <div class="form-group">
    <label>名称</label>
    <input type="text" 
           class="form-control" 
           name="newService[name]" 
           value="<?php echo $service['name']; ?>">
  </div>
  
  <div class="form-group">
    <label>描述</label>
    <textarea class="form-control" name="newService[description]"
    ><?php echo $this->encodeHtmlString($service['description']); ?></textarea>
  </div>
  
  <div class="form-group">
    <label>继承于</label>
    <input type="text" 
           class="form-control" 
           placeholder="该模块类所继承的类名。" 
           name="newService[extends]" 
           value="<?php echo $service['extends']; ?>">
    <?php if( isset($errors['extends'])): ?>
      <p class="text-danger"><?php echo $errors['extends']; ?></p>
    <?php endif; ?>
  </div>
  
  <div class="form-group">
    <label>其他方法</label>
    <textarea class="form-control" name="newService[otherMethods]" rows="10"
    ><?php echo $this->encodeHtmlString($service['otherMethods']); ?></textarea>
  </div>
  
  <div class="form-group">
    <label>使用空格代替TAB</label> &nbsp;&nbsp;&nbsp;
    <input type="hidden" name="newService[useSpace]" value="no">
    <input type="checkbox" name="newService[useSpace]" value="yes" <?php if ('yes'===$service['useSpace']): ?>checked<?php endif;?>>
  </div>
  
  <div class="form-group">
    <label>TAB尺寸</label>
    <input type="text" 
           class="form-control" 
           name="newService[tabSize]" 
           value="<?php echo $service['tabSize']; ?>">
  </div>
  
  <button type="submit" class="btn btn-default">创建</button>
</form>