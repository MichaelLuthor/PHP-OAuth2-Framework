<?php
namespace X\Module\SystemManager\Action\Module;
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
        
        $moduleManager = X::system()->getModuleManager();
        if ( !$moduleManager->has($name) ) {
            HttpError404Handler::run();
        }
        
        $module = $moduleManager->get($name);
        $modulePath = $module->getPath();
        
        $tmpName = SystemManagerHelper::zip($modulePath);
        SystemManagerHelper::sendFileToBrowser($name.'.zip', $tmpName, 'application/zip');
        unlink($tmpName);
    }
}