<?php
namespace X\Module\API;
use X\Core\X;
use X\Core\Module\XModule;
use X\Service\XAction\Service as XActionService;
/**
 * 
 */
class Module extends XModule {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::run()
     */
    public function run($parameters = array()) {
        $actionService = X::system()->getServiceManager()->get(XActionService::getServiceName());
        $namespace = get_class($this);
        $namespace = substr($namespace, 0, strrpos($namespace, '\\'));
        $group = $this->getName();
        $actionService->addGroup($group, $namespace);
        $actionService->getParameterManager()->merge($parameters);
        return $actionService->runGroup($group);
    }

    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::getPrettyName()
     */
    public function getPrettyName() {
        return 'API';
    }

    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::getDescription()
     */
    public function getDescription() {
        return 'API模块';
    }

    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::getVersion()
     */
    public function getVersion() {
        return array(0, 0, 1);
    }
}