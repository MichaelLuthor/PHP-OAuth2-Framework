<?php
namespace X\Module\SystemManager\Action\Service;
use X\Core\X;
use X\Module\SystemManager\Util\Action;
use X\Module\SystemManager\Util\HttpError404Handler;

class UpdateConfiguration extends Action {
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
        
        $config = $this->getParam('service_config_content');
        
        $config = json_decode($config, true);
        if ( null === $config ) {
            return $this->gotoURL('service/detail', array('name'=>$name));
        }
        
        $service->getConfiguration()->removeAll();
        $service->getConfiguration()->setValues($config);
        
        $service->getConfiguration()->save();
        $this->gotoURL('service/detail', array('name'=>$name));
    }
}