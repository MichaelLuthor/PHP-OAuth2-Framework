<?php
namespace X\Service\XView\Core\Util\HtmlView;
/**
 * 
 */
use X\Service\XView\Core\Util\Exception;
use X\Service\XView\Core\Handler\Html;
/**
 * 
 */
class ParticleViewManager {
    /**
     * @var array
     */
    private $particles = array();
    
    /**
     * @var Html
     */
    private $host = null;
    
    /**
     * @param Html $host
     */
    public function __construct( Html $host ) {
        $this->host = $host;
    }
    
    /**
     * @return \X\Service\XView\Core\Handler\Html
     */
    public function getHost() {
        return $this->host;
    }
    
    /**
     * Load paritcle view into current view.
     * @return \X\Service\XView\Core\Util\HtmlView\ParticleView
     */
    public function load( $name, $handler ) {
        $this->particles[$name] = new ParticleView($name, $handler, $this);
        return $this->particles[$name];
    }
    
    /**
     * Chech that whether the paricle exists.
     * @param string $name The name of particle view.
     * @return boolean
     */
    public function has( $name ) {
        return isset($this->particles[$name]);
    }
    
    /**
     * Get the paticle information by given name.
     * @param string $name The particle name.
     * @throws Exception
     * @return \X\Service\XView\Core\Util\HtmlView\ParticleView
     */
    public function get( $name ) {
        if ( !$this->has($name) ) {
            throw new Exception('Can not find particle view "'.$name.'"');
        }
        return $this->particles[$name];
    }
    
    /**
     * Get all particles of current view.
     * @return array
     */
    public function getList() {
        return array_keys($this->particles);
    }
    
    /**
     * Remove particle view from current view.
     * @param string $name The name of particle to remove.
     */
    public function remove( $name ) {
        if ( isset($this->particles[$name]) ) {
            unset($this->particles[$name]);
        }
    }
    
    /**
     * 
     */
    public function toString() {
        $contents = array();
        foreach ( $this->particles as $particle ) {
            $contents[] = $particle->toString();
        }
        $contents = array_filter($contents);
        return implode("\n", $contents);
    }
    
    /**
     * 
     */
    public function cleanUp() {
        foreach ( $this->particles as $particle ) {
            $particle->cleanUp();
        }
    }
}