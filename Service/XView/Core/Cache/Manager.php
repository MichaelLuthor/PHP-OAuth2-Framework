<?php
namespace X\Service\XView\Core\Cache;
use X\Core\X;
use X\Service\XView\Service as XViewService;
/**
 * @method boolean isCacheAvailable($name, $mark=null)
 * @method void cacheContent($name,$content,$mark=null,$lifetime=null)
 * @method clean($name, $mark=null)
 * @method cleanAll()
 * @method string getContent($name, $mark=null)
 */
class Manager {
    /**
     * @var XViewService
     */
    private $service = null;
    
    /**
     * @var unknown
     */
    private $handler = null;
    
    /**
     * 
     */
    public function __construct() {
        $this->service = X::system()->getServiceManager()->get(XViewService::getServiceName());
        
        $config = $this->service->getConfiguration()->get('cache', array());
        if ( !isset($config['enable']) || false===$config['enable'] ) {
            return ;
        }
        
        $handler = $config['handler'];
        if ( class_exists($handler) ) {
            $this->handler = new $handler($config);
        }
        
        $handler = '\\X\\Service\\XView\\Core\\Cache\\Handler\\'.ucfirst($config['handler']);
        if ( class_exists($handler) ) {
            $this->handler = new $handler($config);
        }
    }
    
    /**
     * @param unknown $name
     * @param array $params
     * @return boolean|mixed
     */
    public function __call( $name, $params=array() ) {
        if ( null === $this->handler ) {
            return false;
        }
        
        if ( !method_exists($this->handler, $name) ) {
            return false;
        }
        return call_user_func_array(array($this->handler, $name), $params);
    }
}