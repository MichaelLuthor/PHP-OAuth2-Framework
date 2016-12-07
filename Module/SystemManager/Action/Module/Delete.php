<?php
namespace X\Module\SystemManager\Action\Module;
use X\Core\X;
use X\Module\SystemManager\Util\Action;
use X\Module\SystemManager\Util\HttpError404Handler;
use X\Module\SystemManager\Util\HttpError500Handler;
use X\Module\SystemManager\Util\SystemManagerHelper;

class Delete extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $name = $this->getParam('name');
        
        if ( $this->getModule()->getName() === $name ) {
            HttpError500Handler::run('this module could not be deleted by itself.');
        }
        
        $moduleManager = X::system()->getModuleManager();
        if ( !$moduleManager->has($name) ) {
            HttpError404Handler::run();
        }
        
        $module = $moduleManager->get($name);
        SystemManagerHelper::deletePath($module->getPath());
        $moduleManager->unregister($name);
        $this->gotoURL('module/index');
    }
}