<?php
namespace X\Module\SystemManager\Action\Service;
use X\Core\X;
use X\Module\SystemManager\Util\Action;
use X\Module\SystemManager\Util\HttpError404Handler;
use X\Module\SystemManager\Util\SystemManagerHelper;
use X\Module\SystemManager\Util\HttpError500Handler;

class Advance extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $serviceName = $this->getParam('name');
        $service = X::system()->getServiceManager()->get($serviceName);
        if ( null === $service ) {
            HttpError404Handler::run();
        }
        
        $manager = SystemManagerHelper::getServiceManagementManager($service);
        if ( null === $manager ) {
            HttpError500Handler::run('This service does not support advance management.');
        }
        
        $manager->runAction($this->getModule(), $this->getParam('extaction'), $this->getAllParams());
    }
}