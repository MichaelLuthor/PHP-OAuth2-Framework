<?php
namespace X\Service\XView\Core\Util\HtmlView;
/**
 * 
 */
use X\Service\XView\Core\Util\Exception;
/**
 * 
 */
class ScriptManager {
    /**
     * @var \X\Service\XView\Core\Handler\Html 
     */
    private $view = null;
    
    /**
     * @param \X\Service\XView\Core\Handler\Html $view
     */
    public function __construct( $view ) {
        $this->view = $view;
    }
    
    /**
     * @return \X\Service\XView\Core\Handler\Html
     */
    public function getPageView() {
        return $this->view;
    }
    
    /**
     * This value contains all the script stuff.
     * @var Script[]
     */
    protected $scripts = array();
    
    /**
     * @param string $name
     * @return \X\Service\XView\Core\Util\HtmlView\Script
     */
    public function add( $name ) {
        $script = new Script($this);
        $this->scripts[$name] = $script;
        return $script;
    }
    
    /**
     * @param unknown $name
     * @param unknown $value
     * @return \X\Service\XView\Core\Util\HtmlView\Script
     */
    public function setValue( $name, $value ) {
        $script = new Script($this);
        $this->scripts[$name] = $script;
        $script->setContent(sprintf('var %s=%s;', $name, json_encode($value)));
        return $script;
    }
    
    /**
     * Get all the scripts of current page.
     * @return array
     */
    public function getList() {
        return array_keys($this->scripts);
    }
    
    /**
     * Check that whether the script exists in this view.
     * @param string $identifier The script identifier
     * @return boolean
     */
    public function has( $name ) {
        return isset($this->scripts[$name]);
    }
    
    /**
     * Get script information from this view.
     * @param string $identifier The script identifier
     * @return array
     */
    public function get( $name ) {
        if ( !$this->has($name) ) {
            throw new Exception('Can not find script "'.$name.'".');
        }
        return $this->scripts[$name];
    }
    
    /**
     * Remove Script from current page.
     * @param string $identifier The name of script
     */
    public function remove($name) {
        if ( isset($this->scripts[$name]) ) {
            unset($this->scripts[$name]);
        }
    }
    
    /**
     * @var array
     */
    private $scriptStrings = array();
    
    /**
     * Get the content of scripts
     * @return string
     */
    public function toString() {
        $this->scriptStrings = array();
        foreach ( $this->scripts as $name => $script ) {
            $this->renderScript($name);
        }
        if ( empty($this->scriptStrings) ) {
            return null;
        }
        $scriptList = implode("\n", $this->scriptStrings);
        return $scriptList;
    }
    
    /**
     * @param string $name
     */
    private function renderScript( $name ) {
        if ( isset($this->scriptStrings[$name]) ) {
            return;
        }
        
        if ( !isset($this->scripts[$name]) ) {
            throw new Exception('Script "'.$name.'" does not exists.');
        }
        
        $script = $this->scripts[$name];
        $requirements = $script->getRequirements();
        foreach ( $requirements as $requirement ) {
            $this->renderScript($requirement);
        }
        
        $this->scriptStrings[$name] = $script->toString();
    }
}