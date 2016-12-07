<?php
/** @var $this \X\Service\XView\Core\Handler\Html */
$particleManager = $this->getParticleViewManager();
foreach ( $particleManager->getList() as $particleName ) {
    $particleManager->get($particleName)->display();
}
?>