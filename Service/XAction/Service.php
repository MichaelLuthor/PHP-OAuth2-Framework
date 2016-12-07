<?php
namespace X\Service\XAction;
use X\Core\Util\ConfigurationArray;
use X\Service\XAction\Core\Exception;
/**
 * XActionService use to handle the Action request and 
 * execute the Action.
 * @author  Michael Luthor <michaelluthor@163.com>
 */
class Service extends \X\Core\Service\XService {
    /**
     * The name of current service.
     * @var string
     */
    protected static $serviceName = 'XAction';
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::getPrettyName()
     */
    public function getPrettyName() {
        return '动作处理器管理服务';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::getDescription()
     */
    public function getDescription() {
        return '根据给定的参数执行动作处理器。';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::start()
     */
    public function start() {
        parent::start();
        $this->parameterManager = new ConfigurationArray();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::stop()
     */
    public function stop() {
        $this->parameterManager = null;
        parent::stop();
    }
    
    /**
     * This value contains all parameter to action handler.
     * @var ConfigurationArray
     */
    private $parameterManager = null;
    
    /**
     * Get current parameter manager.
     * @return ConfigurationArray
     */
    public function getParameterManager() {
        return $this->parameterManager;
    }
    
    /**
     * Group informations.
     * @var array
     */
    private $groups = array();
    
    /**
     * Add action group to service.
     * @param string $name
     * @param string $namespace
     * @throws Exception
     * @return void
     */
    public function addGroup( $name, $namespace ) {
        if ( $this->hasGroup($name) ) {
            throw new Exception('Action group "'.$name.'" already exists.');
        }
        
        $group = array();
        $group['namespace']             = $namespace;
        $group['default']               = null;
        $group['running']               = null;
        $group['registered_actions']    = array();
        $this->groups[$name]            = $group;
    }
    
    /**
     * chekck if group already exists.
     * @param string $name
     * @return boolean
     */
    public function hasGroup( $name ) {
        return isset($this->groups[$name]);
    }
    
    /**
     * Run default action of group by given name.
     * @param string $name
     * @throws Exception
     * @return mixed
     */
    public function runGroup($name){
        if ( !$this->hasGroup($name) ) {
            throw new Exception('Action group "'.$name.'" does not exists.');
        }
    
        $actionName = $this->groups[$name]['default'];
        $actionName = $this->getParameterManager()->get('action', $actionName);
        if ( empty($actionName) ) {
            throw new Exception('Can not find available action in group "'.$name.'".');
        }
    
        return $this->runAction($name, $actionName);
    }
    
    /**
     * @var \X\Service\XAction\Core\Action
     */
    private $runningAction = null;
    
    /**
     * @return  \X\Service\XAction\Core\Action
     */
    public function getRunningAction() {
        return $this->runningAction;
    }
    
    /**
     * Run action by given name in given group.
     * @param string $group
     * @param string $action
     * @return mixed
     */
    public function runAction($group, $action) {
        $this->log('run action "%s" in group "%s"', $action, $group);
        
        $action = $this->getActionByName($group, $action);
        $this->groups[$group]['running'] = $action;
        $parameters = $this->getParameterManager()->toArray();
        unset($parameters['action']);
        $this->runningAction = $action;
        $result = $action->run($parameters);
        $this->runningAction = null;
        return $result;
    }
    
    /**
     * Set default action by given name to a group.
     * @param string $groupName
     * @param string $action
     * @throws Exception
     */
    public function setGroupDefaultAction( $groupName, $action ){
        $this->log('set group default action to "%s" for group "%s".', $action, $groupName);
        
        if ( !$this->hasGroup($groupName) ) {
            throw new Exception('Action group "'.$groupName.'" does not exists.');
        }
        $this->groups[$groupName]['default'] = $action;
    }
    
    /**
     * Register a action into given group.
     * @param string $group
     * @param string $action
     * @param string $handler
     */
    public function register( $group, $action, $handler ) {
        $this->log('register action "%s" to group "%s".', $action, $group);
        
        if ( !$this->hasGroup($group) ) {
            throw new Exception('Action group "'.$group.'" does not exists.');
        }
        $this->groups[$group]['registered_actions'][$action] = $handler;
    }
    
    /**
     * Get action instance by given name from given group.
     * @param string $group
     * @param string $action
     * @throws Exception
     * @return \X\Service\XAction\Core\Action
     */
    public function getActionByName( $group, $action ) {
        if ( isset($this->groups[$group]['registered_actions'][$action]) ) {
            $handler = $this->groups[$group]['registered_actions'][$action];
            if ( class_exists($handler) ) {
                $handler = new $handler($group, $action);
                return $handler;
            }
        }
        
        $actionClass = explode('/', $action);
        $actionClass = array_map('ucfirst', $actionClass);
        $actionClass = implode('\\', $actionClass);
        $namespace = $this->groups[$group]['namespace'].'\\Action';
        $actionClass = $namespace.'\\'.$actionClass;
        if ( class_exists($actionClass, true) ) {
            $action = new $actionClass($group, $action);
            return $action;
        }
        
        throw new Exception('Can not find Action "'.$action.'" in group "'.$group.'".');
    }
}