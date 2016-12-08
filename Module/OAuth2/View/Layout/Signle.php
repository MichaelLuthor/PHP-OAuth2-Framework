<?php
/** @var $this \X\Service\XView\Core\Handler\Html */
$linkManager = $this->getLinkManager();
$linkManager->addCSS('bootstrap', '//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css');
$linkManager->addCSS('bootstrap-theme', '//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap-theme.css');

$scriptManager = $this->getScriptManager();
$scriptManager->add('jquery')->setSource('//cdn.bootcss.com/jquery/3.1.1/jquery.js');
$scriptManager->add('bootstrap')->setSource('//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.js');
?>
<div class="container-fluid">
  <div class="row">
    <?php 
    $particleManager = $this->getParticleViewManager();
    foreach ( $particleManager->getList() as $particleName ) {
        $particleManager->get($particleName)->display();
    }
    ?>
  </div>
</div>