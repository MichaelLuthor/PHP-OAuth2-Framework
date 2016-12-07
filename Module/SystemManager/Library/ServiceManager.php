<?php
namespace X\Module\SystemManager\Library;

use X\Module\SystemManager\Util\SystemManagerHelper;
use X\Module\SystemManager\Util\HttpError404Handler;
/**
 * 
 */
abstract class ServiceManager {
    /**
     * @var unknown
     */
    private $service = null;
    
    /**
     * @param unknown $serivce
     */
    public function __construct( $serivce ) {
        $this->service = $serivce;
    }
    
    /**
     * @return \X\Core\Service\XService
     */
    public function getService() {
        return $this->service;
    }
    
    /**
     * Get the list of extended management tool list.
     * here is an example to define it:
     * <pre>
     * array(
     *  'ActionName' => array('name'=>'Display name'),
     *  'check' => array('name'=>'Check Configuration'),
     *  'createNewView' => array('name'=>'Create new View'),
     * );
     * </pre>
     * 
     * @return array
     */
    public function getExtendedManagementTools() {
        return array();
    }
    
    /**
     * @param string $name
     */
    public function runAction( $module, $name, $parameters ) {
        $action = implode('\\', array_map('ucfirst', explode('/', $name)));
        
        $namespace = SystemManagerHelper::getClassNamespaceName($this->getService()).'\\Management\\Action\\';
        $actionHandlerName = $namespace.ucfirst($action);
        
        if ( !class_exists($actionHandlerName,true) ) {
            HttpError404Handler::run();
        }
        
        if ( !is_subclass_of($actionHandlerName, 'X\\Module\\SystemManager\\Library\\ServiceManagementAction') ) {
            HttpError404Handler::run();
        }
        
        /* @var $handler \X\Module\SystemManager\Library\ServiceManagementAction */
        $handler = new $actionHandlerName($module, $parameters);
        $handler->setService($this->getService());
        $handler->run();
    }
}