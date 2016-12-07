<?php
namespace X\Module\SystemManager;
use X\Core\Module\XModule;
use X\Module\SystemManager\Util\HttpError404Handler;

/**
 * 
 */
class Module extends XModule {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::run()
     */
    public function run($parameters = array()) {
        $action = 'index';
        if ( isset($parameters['action']) ) {
            $action = $parameters['action'];
        }
        
        if ( PHP_SESSION_NONE === session_status() ) {
            session_start();
        }
        
        $action = implode('\\', array_map('ucfirst', explode('/', $action)));
        $namespace = 'X\\Module\\SystemManager\\Action\\';
        $actionHandlerName = $namespace.ucfirst($action);
        
        if ( !class_exists($actionHandlerName,true) ) {
            HttpError404Handler::run();
        }
        
        if ( !is_subclass_of($actionHandlerName, 'X\\Module\\SystemManager\\Util\\Action') ) {
            HttpError404Handler::run();
        }
        
        /* @var $handler \X\Module\SystemManager\Util\Action */
        $handler = new $actionHandlerName($this, $parameters);
        $handler->run();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::getPrettyName()
     */
    public function getPrettyName() {
        return '框架管理';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::getDescription()
     */
    public function getDescription() {
        return '方便快捷的管理X框架的各种模块与服务。';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Module\XModule::getVersion()
     */
    public function getVersion() {
        return array(0, 0, 1);
    }
}