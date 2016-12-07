<?php
namespace X\Module\SystemManager\Action\Service;
use X\Core\X;
use X\Module\SystemManager\Util\Action;

class Index extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $serviceNames = X::system()->getServiceManager()->getList();
        $services = array();
        foreach ( $serviceNames as $name ) {
            $services[$name] = X::system()->getServiceManager()->get($name);
        }
        
        $this->render('Service/Index', array('services'=>$services));
    }
}