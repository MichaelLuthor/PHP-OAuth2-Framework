<?php
namespace X\Core\Event;

/**
 * 
 */
use X\Core\Util\Exception;
use X\Core\Util\Manager as UtilManager;

/**
 * 
 */
class Manager extends UtilManager {
    /**
     * @var array
     */
    private $eventHandlers = array(
        /* 'event-nane' => array('event', 'handler') */
    );
    
    /**
     * @param string $eventName
     * @param callable $eventHandler
     * @throws Exception
     */
    public function registerHandler( $eventName, $eventHandler ) {
        if ( !is_callable($eventHandler) ) {
            throw new Exception('Event handler is not callable.');
        }
        
        if ( !isset($this->eventHandlers[$eventName]) ) {
            $this->eventHandlers[$eventName] = array();
        }
        
        $this->eventHandlers[$eventName][] = $eventHandler;
    }
    
    /**
     * @param string $eventName
     * @param mixed $param1
     * @param mixed $param2,...
     * @return array
     */
    public function trigger( $eventName ) {
        if ( !isset($this->eventHandlers[$eventName]) ) {
            return array();
        }
        
        $parameters = func_get_args();
        array_shift($parameters);
        
        $result = array();
        foreach ( $this->eventHandlers[$eventName] as $handler ) {
            $result[] = call_user_func_array($handler, $parameters);
        }
        return $result;
    }
}