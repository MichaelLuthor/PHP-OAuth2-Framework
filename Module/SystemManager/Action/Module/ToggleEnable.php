<?php
namespace X\Module\SystemManager\Action\Module;
use X\Core\X;
use X\Module\SystemManager\Util\Action;
use X\Module\SystemManager\Util\HttpError404Handler;
use X\Module\SystemManager\Util\HttpError500Handler;

class ToggleEnable extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $name = $this->getParam('name');
        if ( $name === $this->getModule()->getName() ) {
            HttpError500Handler::run('该模块无法通过该面板禁用。');
        }
        
        $module = X::system()->getModuleManager()->get($name);
        if ( null === $module ) {
            HttpError404Handler::run();
        }
        
        if ( $module->isEnabled() ) {
            $module->disable();
        } else {
            $module->enable();
        }
        
        $this->gotoURL('module/detail', array('name'=>$name));
    }
}