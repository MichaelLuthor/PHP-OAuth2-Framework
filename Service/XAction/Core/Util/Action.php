<?php
/**
 * This file is part of x-action service.
 * @license LGPL http://www.gnu.de/documents/lgpl.en.html
 */
namespace X\Service\XAction\Core\Util;
/**
 * 
 */
use X\Core\Util\MethodExtendable;
use X\Service\XAction\Core\Exception;
/**
 * The action class for XAction service.
 * @author Michael Luthor <michael.the.ranidae@gmail.com>
 * @method mixed runAction()
 */
abstract class Action extends MethodExtendable {
    /**
     * The name of group that this action belongs.
     * @var string
     */
    private $groupName = null;
    
    /**
     * Get the name of group of action.
     * @return string
     */
    public function getGroupName() {
        return $this->groupName;
    }
    
    /**
     * @var unknown
     */
    private $name = null;
    
    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Initiate the action class.
     * @param string $groupName The name of group that this action belongs.
     */
    public function __construct( $groupName, $name) {
        $this->groupName = $groupName;
        $this->name = $name;
        $this->init();
    }
    
    /**
     * @var unknown
     */
    protected $result = null;
    
    /**
     * 
     */
    protected function init() {}
        
    /**
     * This value contains all parameter to the action.
     * @var array
     */
    private $parameters = array();
    
    /**
     * Get parameter value by given name.
     * @param string $name
     * @return mixed
     */
    public function getParameter( $name, $default=null ) {
        return key_exists($name, $this->parameters) ? $this->parameters[$name] : $default;
    }
    
    /**
     * Execute this Action. If beforeRunAction returns false, then
     * it would not execute the Action and return false as Action 
     * result. Also, if Action implement method returns false, then
     * afterRunAction method would not be executed.
     * Notice, You should overwrite 'runAction' method to implment
     * the processing of Action. If there is not runAction method,
     * it would not give you any error message, but it would still
     * execute the afterRunAction method.
     * Also, you can redifine the ACTION_HANDLER_NAME to rename the
     * Action name of implemention.
     * @param array $parameters
     * @return boolean|unknown
     */
    public function run( $parameters=array() ){
        $this->parameters = $parameters;
        if ( false === $this->beforeRunAction() ) {
            return false;
        }
        $this->result = $this->doRunAction($parameters);
        $this->afterRunAction();
        return $this->result;
    }
    
    /**
     * Execute the implement Action.
     * @param array $parameters
     * @return boolean|mixed
     */
    protected function doRunAction($parameters) {
        $handlerName = 'runAction';
        if ( !method_exists($this, $handlerName) || !is_callable(array($this, $handlerName)) ) {
            throw new Exception("Can not find action handler \"runAction()\".");
        }
        
        $paramsToMethod = array();
        $class = new \ReflectionClass($this);
        $method = $class->getMethod($handlerName);
        
        $parameterInfos = $method->getParameters();
        foreach ( $parameterInfos as $parmInfo ) {
            /* @var $parmInfo \ReflectionParameter */
            $name = $parmInfo->getName();
            if ( isset($parameters[$name]) ) {
                $paramsToMethod[$name] = $parameters[$name];
            } else if ( $parmInfo->isOptional() && $parmInfo->isDefaultValueAvailable() ) {
                $paramsToMethod[$name] = $parmInfo->getDefaultValue();
            } else {
                throw new Exception('Parameters to action handler is not available.');
            }
        }
        
        $handler = array($this, $handlerName);
        return \call_user_func_array($handler, $paramsToMethod);
    }
    
    /**
     * The method that would be called before the action executed.
     * @return void
     */
    protected function beforeRunAction(){
        return true;
    }
    
    /**
     * The method would be called after the action executed.
     * @return void
     */
    protected function afterRunAction(){}
}