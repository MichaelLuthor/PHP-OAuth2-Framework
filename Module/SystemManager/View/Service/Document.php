<?php 
/* @var $this \X\Module\SystemManager\Action\Service\Detail */

$vars = get_defined_vars();
/* @var $service \X\Core\Service\XService */
$service = $vars['service'];
$docContent = $vars['docContent'];
$serviceID = $service->getName();
?>

<ol class="breadcrumb">
  <li><a href="<?php echo $this->createURL('index')?>">管理主页</a></li>
  <li><a href="<?php echo $this->createURL('service/index')?>">服务管理</a></li>
  <li>
    <a href="<?php echo $this->createURL('service/detail', array('name'=>$serviceID));?>">
      <?php echo $this->encodeHtmlString($service->getPrettyName()); ?>
    </a>
  </li>
  <li class="active">文档</li>
</ol>

<?php if (null === $docContent) : ?>
  <strong>暂无相关文档</strong>
<?php else : ?>
  <div>
    <?php echo $docContent; ?>
  </div>
  <br>
  <br>
  <br>
<?php endif;?>