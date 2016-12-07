<?php
namespace X\Core\Util;
/**
 * 
 */
abstract class MethodExtendable {
    /**
     * @var array
     */
    private $bindedMethods = array();
    
    /**
     * @param string $handler
     * @throws Exception
     */
    public function bindMethodHandler( $handler ) {
        $handlerInstance = new $handler($this);
        if ( !($handlerInstance instanceof MethodExtendHandler) ) {
            throw new Exception('method should be extens from "X\\Core\\Util\\MethodExtendHandler".');
        }
        
        $handlerInfo = new \ReflectionClass($handlerInstance);
        $methods = $handlerInfo->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        foreach ( $methods as $method ) {
            /* @var $method \ReflectionMethod */
            if ( $method->isStatic() ) {
                continue;
            }
            
            $methodName = $method->name; 
            if ( '__' === substr($methodName, 0, 2) ) {
                continue;
            }
            
            if ( method_exists($this, $methodName) || isset($this->bindedMethods[$methodName]) ) {
                throw new Exception('method "'.$methodName.'" alread exists in "'.get_class($this).'"');
            }
            
            $this->bindedMethods[$methodName] = array($handlerInstance, $methodName);
        }
    }
    
    /**
     * @param unknown $name
     * @param unknown $params
     * @throws Exception
     * @return mixed
     */
    public function __call( $name, $params ) {
        if ( isset($this->bindedMethods[$name]) ) {
            return call_user_func_array($this->bindedMethods[$name], $params);
        } else {
            throw new Exception('method "'.$name.'" does not exists in "'.get_class($this).'".');
        }
    }
}