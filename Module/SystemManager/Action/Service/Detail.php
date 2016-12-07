<?php
namespace X\Module\SystemManager\Action\Service;
use X\Core\X;
use X\Module\SystemManager\Util\Action;
use X\Module\SystemManager\Util\HttpError404Handler;
use X\Module\SystemManager\Util\SystemManagerHelper;

class Detail extends Action {
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
        
        $manager = SystemManagerHelper::getServiceManagementManager($service);
        $this->render('Service/Detail', array('service'=>$service, 'manager'=>$manager));
    }
}