<?php
namespace X\Core\Util;
/**
 * 
 */
abstract class MethodExtendHandler {
    /**
     * @var \X\Core\Util\Basic
     */
    private $hostInstance = null;
    
    /**
     * @var unknown
     */
    private $option = array();
    
    /**
     * @param unknown $host
     */
    public function __construct( $host, $option=array() ) {
        $this->hostInstance = $host;
        $this->option = $option;
    }
    
    /**
     * @param unknown $name
     * @param unknown $default
     */
    protected function getOption( $name, $default=null ) {
        return isset($this->option[$name]) ? $this->option[$name] : $default; 
    }
    
    /**
     * @return \X\Core\Util\Basic
     */
    protected function getHostInstance() {
        return $this->hostInstance;
    }
    
    /**
     * @return string
     */
    public static function getClassName() {
        return get_called_class();
    }
}