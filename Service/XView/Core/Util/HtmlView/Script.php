<?php
namespace X\Service\XView\Core\Util\HtmlView;
/**
 * 
 */
class Script {
    /**
     * @var ScriptManager
     */
    private $manager = null;
    
    /**
     * @param ScriptManager $view
     */
    public function __construct( $manager ) {
        $this->manager = $manager;
    }
    
    /**
     * @var string
     */
    private $type = 'text/javascript';
    
    /**
     * @param string $type
     * @return \X\Service\XView\Core\Util\HtmlView\Script
     */
    public function setType( $type ) {
        $this->type = $type;
        return $this;
    }
    
    /**
     * @var string
     */
    private $src = null;
    
    /**
     * @param string $path
     * @return \X\Service\XView\Core\Util\HtmlView\Script
     */
    public function setSource( $path ) {
        $this->src = $path;
        return $this;
    }
   
    /**
     * @var string
     */
    private $content = null;
    
    /**
     * @param string $content
     * @return \X\Service\XView\Core\Util\HtmlView\Script
     */
    public function setContent( $content ) {
        $this->content = $content;
        return $this;
    }
    
    /**
     * @var boolean
     */
    private $defer = false;
    
    /**
     * @return \X\Service\XView\Core\Util\HtmlView\Script
     */
    public function enableDefer() {
        $this->defer = true;
        return $this;
    }
    
    /**
     * @var string
     */
    private $charset = 'UTF-8';
    
    /**
     * @param string $charset
     * @return \X\Service\XView\Core\Util\HtmlView\Script
     */
    public function setCharset( $charset ) {
        $this->charset = $charset;
        return $this;
    }
    
    /**
     * @var boolean
     */
    private $asyns = false;
    
    /**
     * @return \X\Service\XView\Core\Util\HtmlView\Script
     */
    private function enableAsyns() {
        $this->asyns = true;
        return $this;
    }
    
    /**
     * @var array
     */
    private $requirements = array();
    
    /**
     * @param string $name
     * @param string $name2
     * @param string $...
     * @return \X\Service\XView\Core\Util\HtmlView\Script
     */
    public function setRequirements($name) {
        $this->requirements = func_get_args();
        return $this;
    }
    
    /**
     * @return array
     */
    public function getRequirements() {
        return $this->requirements;
    }
    
    /**
     * @return string
     */
    public function toString() {
        if ( null !== $this->src ) {
            return '<script type="'.$this->type.'" src="'.$this->src.'" charset="'.$this->charset.'"></script>';
        } else if ( null !== $this->content ) {
            return "<script type=\"{$this->type}\">\n{$this->content}\n</script>";
        } else {
            return null;
        }
    }
}