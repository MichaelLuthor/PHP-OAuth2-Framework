<?php
namespace X\Module\SystemManager\Action\Service;
use X\Core\X;
use X\Module\SystemManager\Util\Action;
use X\Module\SystemManager\Util\HttpError404Handler;

class ToggleEnable extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $name = $this->getParam('name');
        
        $service = X::system()->getServiceManager()->get($name);
        if ( null === $service ) {
            HttpError404Handler::run();
        }
        
        if ( $service->isEnabled() ) {
            $service->disable();
        } else {
            $service->enable();
        }
        
        $this->gotoURL('service/detail', array('name'=>$name));
    }
}