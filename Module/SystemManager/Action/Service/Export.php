<?php
namespace X\Module\SystemManager\Action\Service;
use X\Core\X;
use X\Module\SystemManager\Util\Action;
use X\Module\SystemManager\Util\HttpError404Handler;
use X\Module\SystemManager\Util\SystemManagerHelper;

class Export extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $name = $this->getParam('name');
        
        $serviceManager = X::system()->getServiceManager();
        if ( !$serviceManager->has($name) ) {
            HttpError404Handler::run();
        }
        
        $service = $serviceManager->get($name);
        $servicePath = $service->getPath();
        
        $tmpName = SystemManagerHelper::zip($servicePath);
        SystemManagerHelper::sendFileToBrowser($name.'.zip', $tmpName, 'application/zip');
        unlink($tmpName);
    }
}