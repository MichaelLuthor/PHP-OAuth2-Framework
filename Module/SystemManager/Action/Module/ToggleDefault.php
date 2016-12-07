<?php
namespace X\Module\SystemManager\Action\Module;
use X\Core\X;
use X\Module\SystemManager\Util\Action;
use X\Module\SystemManager\Util\HttpError404Handler;

class ToggleDefault extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $name = $this->getParam('name');
        $module = X::system()->getModuleManager()->get($name);
        if ( null === $module ) {
            HttpError404Handler::run();
        }
        
        if ( $module->isDefaultModule() ) {
            $module->unsetAsDefault();
        } else {
            $module->setAsDefault();
        }
        $this->gotoURL('module/detail', array('name'=>$name));
    }
}