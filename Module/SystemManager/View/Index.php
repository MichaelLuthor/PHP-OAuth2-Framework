<?php /* @var $this \X\Module\SystemManager\Action\Index */ ?>

<ol class="breadcrumb">
  <li class="active">管理首页</li>
</ol>

<div class="col-md-3">
<?php echo '<a href="',$this->createURL('module/index'), '">'; ?>
  <div class="well well-sm text-center">
  <br><br>模块管理<br><br><br>
  </div>
<?php echo '</a>'; ?>
</div>

<div class="col-md-3">
<?php echo '<a href="',$this->createURL('service/index'), '">'; ?>
  <div class="well well-sm text-center">
  <br><br>服务管理<br><br><br>
  </div>
<?php echo '</a>'; ?>
</div>

<div class="col-md-3">
<?php echo '<a href="',$this->createURL('tool/index'), '">'; ?>
  <div class="well well-sm text-center">
  <br><br>快捷工具<br><br><br>
  </div>
<?php echo '</a>'; ?>
</div>