<?php
namespace X\Core\Util;
/**
 * 
 */
class ParameterChecker {
    /**
     * @param array $parameters
     * @return \X\Core\Util\ParameterChecker
     */
    public static function check($parameters, $method) {
        return new ParameterChecker($parameters, $method);
    }
    
    /**
     * @var array
     */
    private $parameters = array();
    
    /**
     * @var string
     */
    private $methodName = null;
    
    /**
     * @param array $parameters
     */
    protected function __construct($parameters, $methodName) {
        $method = new \ReflectionMethod($methodName);
        $methodParams = $method->getParameters();
        foreach ( $methodParams as $index => $methodParam ) {
            /* @var $methodParam \ReflectionParameter */
            $parameValue = null;
            if ( isset($parameters[$index]) ) {
                $parameValue = $parameters[$index];
            } else {
                $parameValue = $methodParam->getDefaultValue();
            }
            $this->parameters[$methodParam->getName()] = $parameValue;
        }
        $this->methodName = $methodName;
    }
    
    /**
     * @param string $name
     * @return \X\Core\Util\ParameterChecker
     */
    public function notEmpty($name) {
        if ( empty($this->getValue($name)) ) {
            throw new Exception('Parameter $'.$name.' to '.$this->methodName.' can not be empty.');
        }
        return $this;
    }
    
    /**
     * @param string $name
     * @return \X\Core\Util\ParameterChecker
     */
    public function isArray( $name ){
        if ( !is_array($this->getValue($name)) ) {
            throw new Exception('Parameter $'.$name.' to '.$this->methodName.' must be an array.');
        }
        return $this;
    }
    
    /**
     * @param string $name
     * @throws Exception
     * @return \X\Core\Util\ParameterChecker
     */
    public function isString( $name ){
        $value = $this->getValue($name);
        $isString = is_string($value);
        $isString = $isString || is_numeric($value);
        $isString = $isString || ( is_object($value) && is_callable(array($value, '__toString')) );
        $isString = $isString || ( is_object($value) && is_callable(array($value, 'toString')) );
        if ( !$isString ) {
            throw new Exception('Parameter $'.$name.' to '.$this->methodName.' must be a string.');
        }
        
        return $this;
    }
    
    /**
     * @param unknown $name
     * @return \X\Core\Util\ParameterChecker
     */
    public function isBoolean( $name ) {
        if ( !is_bool($this->getValue($name)) ) {
            $this->throwException($name, 'must be a boolean.');
        }
        return $this;
    }
    
    /**
     * @param unknown $name
     * @throws Exception
     * @return \X\Core\Util\ParameterChecker
     */
    public function isInteger( $name ) {
        $value = $this->getValue($name);
        if ( is_int($value) ) {
            throw new Exception('Parameter $'.$name.' to '.$this->methodName.' must be a integer.');
        }
        return $this;
    }
    
    /**
     * @param unknown $name
     * @return \X\Core\Util\ParameterChecker
     */
    public function isNull( $name ) {
        if ( null !== $this->getValue($name) ) {
            $this->throwException($name, 'must be null');
        }
        return $this;
    }
    
    /**
     * @param unknown $name
     * @param unknown $types
     * @return \X\Core\Util\ParameterChecker
     */
    public function checkType( $name, $types ) {
        $isValidated = false;
        
        foreach ( $types as $type ) {
            $handler = 'is'.ucfirst($type);
            $handler = array($this, $handler);
            if ( is_callable($handler) ) {
                try {
                    call_user_func_array($handler, array($name));
                    $isValidated = true;
                    break;
                } catch ( Exception $e ) {}
            }
        }
        
        if ( !$isValidated ) {
            $this->throwException($name, 'must be '.implode('|', $types));
        }
        return $this;
    }
    
    const T_STRING = 'string';
    const T_ARRAY = 'array';
    const T_INTEGER = 'integer';
    const T_NULL = 'null';
    
    /**
     * @param unknown $name
     * @param unknown $callback
     * @param unknown $message
     * @throws Exception
     * @return \X\Core\Util\ParameterChecker
     */
    public function validate( $name, $callbacks, $message ) {
        if ( !is_array($callbacks) || is_callable($callbacks) ) {
            $callbacks = array($callbacks);
        }
        
        $isValidated = false;
        foreach ( $callbacks as $callback ) {
            if ( !is_callable($callback) ) {
                throw new Exception('validate handler is not a validate callback.');
            }
            
            if ( call_user_func_array($callback, array($this->getValue($name))) ) {
                $isValidated = true;
                break;
            }
        }
        
        if ( !$isValidated ) {
            $this->throwException($name, 'validate failed', $message);
        }
        return $this;
    }
    
    /**
     * @param string $name
     * @return mixed
     */
    private function getValue( $name ) {
        preg_match('/(^[a-zA-Z0-9_]+)(.*$)/', $name, $matched);
        
        $key = $matched[1];
        $exten = $matched[2];
        $accessCode = 'return $this->parameters["'.$key.'"]'.$exten.';';
        $value = eval($accessCode);
        return $value;
    }
    
    /**
     * @param string $name
     * @param string $message
     * @throws Exception
     */
    private function throwException($name, $message) {
        throw new Exception('Parameter $'.$name.' to '.$this->methodName.' '.$message.'.');
    }
}