<?php
namespace X\Module\SystemManager\Library;
use X\Core\X;
use X\Module\SystemManager\Util\Action;

abstract class ServiceManagementAction extends Action {
    /**
     * @var \X\Core\Service\XService
     */
    private $service = null;
    
    /**
     * @param \X\Core\Service\XService $service
     */
    public function setService( \X\Core\Service\XService $service ) {
        $this->service = $service;
    }
    
    /**
     * @return \X\Core\Service\XService
     */
    public function getService() {
        return $this->service;
    }
    
    /**
     * @param unknown $name
     * @return \X\Core\Service\XService
     */
    public function getServiceByName($name) {
        return X::system()->getServiceManager()->get($name);
    }
    
    /**
     * @param unknown $name
     * @return \X\Core\Module\XModule
     */
    public function getModuleByName($name) {
        return X::system()->getModuleManager()->get($name);
    }
    
    /**
     * @param unknown $name
     * @param unknown $data
     */
    public function render( $name, $data=array() ) {
        $viewPath = $this->getService()->getPath('Management/View/'.$name.'.php');
        $content = $this->renderView($viewPath, $data);
        
        $layoutPath = $this->getModule()->getPath('View/Layout/Default.php');
        $layoutData = array('content'=>$content, 'head'=>$this->layoutHeader);
        echo $this->renderView($layoutPath, $layoutData);
    }
    
    /**
     * @param unknown $action
     * @param unknown $params
     * @param string $service
     * @return string
     */
    public function createServiceAdvanceURL($action, $params=array(), $service=null) {
        $service = (null===$service) ? $this->getService() : $service;
        
        $urlParams = array();
        $urlParams['name']= is_string($service) ? $service : $service->getName();
        $urlParams['extaction'] = $action;
        $urlParams = array_merge($urlParams, $params);
        return $this->createURL('service/advance', $urlParams);
    }
    
    /**
     * @param unknown $service
     * @param unknown $action
     * @param unknown $params
     */
    public function gotoServiceAdvanceURL($action, $params=array(), $service=null) {
        header("Location: ".$this->createServiceAdvanceURL($action, $params, $service));
        X::system()->stop();
    }
    
    /**
     * @return array
     */
    public function getAllServices() {
        $serviceNames = X::system()->getServiceManager()->getList();
        $services = array();
        foreach ( $serviceNames as $serviceName ) {
            $services[$serviceName] = $this->getServiceByName($serviceName);
        }
        return $services;
    }
    
    /**
     * @return array
     */
    public function getAllModules() {
        $moduleNames = X::system()->getModuleManager()->getList();
        $modules = array();
        foreach ( $moduleNames as $moduleName ) {
            $modules[$moduleName] = $this->getModuleByName($moduleName);
        }
        return $modules;
    }
}