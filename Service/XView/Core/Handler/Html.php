<?php
/**
 * Namespace defination
 */
namespace X\Service\XView\Core\Handler;

/**
 * Use statement
 */
use X\Service\XView\Core\Util\HtmlView\StyleManager;
use X\Service\XView\Core\Util\HtmlView\LinkManager;
use X\Service\XView\Core\Util\HtmlView\MetaManager;
use X\Service\XView\Core\Util\HtmlView\ScriptManager;
use X\Service\XView\Core\Util\HtmlView\ParticleViewManager;
use X\Core\Util\ConfigurationArray;
use X\Service\XView\Core\Util\HtmlFormatter;
/**
 * The view handler for html page.
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version Version 0.0.0
 */
class Html extends \X\Service\XView\Core\Util\View {
    /**
     * @var StyleManager
     */
    private $styleManager = null;
    
    /**
     * @var LinkManager
     */
    private $linkManager = null;
    
    /**
     * @var MetaManager
     */
    private $metaManager = null;
    
    /**
     * @var ScriptManager
     */
    private $scriptManager = null;
    
    /**
     * @var ParticleViewManager
     */
    private $particleViewManager = null;
    
    /**
     * @var ConfigurationArray
     */
    private $dataManager = null;
    
    /**
     * @var string
     */
    private $format = null;
    
    /**
     * @param unknown $type
     */
    public function format( $type ) {
        $this->format = $type;
    }
    
    /**
     * 
     */
    public function __construct() {
        $this->styleManager = new StyleManager();
        $this->linkManager = new LinkManager($this);
        $this->metaManager = new MetaManager();
        $this->scriptManager = new ScriptManager($this);
        $this->particleViewManager = new ParticleViewManager($this);
        $this->dataManager = new ConfigurationArray();
    }
    
    /**
     * @return \X\Service\XView\Core\Util\HtmlView\StyleManager
     */
    public function getStyleManager() {
        return $this->styleManager;
    }
    
    /**
     * @return \X\Service\XView\Core\Util\HtmlView\LinkManager
     */
    public function getLinkManager() {
        return $this->linkManager;
    }
    
    /**
     * @return \X\Service\XView\Core\Util\HtmlView\MetaManager
     */
    public function getMetaManager() {
        return $this->metaManager;
    }
    
    /**
     * @return \X\Service\XView\Core\Util\HtmlView\ScriptManager
     */
    public function getScriptManager() {
        return $this->scriptManager;
    }
    
    /**
     * @return \X\Service\XView\Core\Util\HtmlView\ParticleViewManager
     */
    public function getParticleViewManager() {
        return $this->particleViewManager;
    }
    
    /**
     * @return \X\Core\Util\ConfigurationArray
     */
    public function getDataManager() {
        return $this->dataManager;
    }
    
    /**
     * @var string
     */
    private $assetsBaseURL = 'assets';
    
    /**
     * @param string $url
     * @return \X\Service\XView\Core\Handler\Html
     */
    public function setAssetsBaseURL( $url ) {
        $this->assetsBaseURL = $url;
        return $this;
    }
    
    /**
     * @param string $path
     * @return string
     */
    public function getAsset( $path ) {
        return $this->assetsBaseURL.'/'.$path;
    }
    
    /**
     * Convert this object to string.
     * @return string
     */
    public function toString() {
        if ( null === $this->layout['view'] ) {
            return null;
        }
        
        $layoutContent = $this->renderLayout();
        
        $content = array();
        $content[] = '<!DOCTYPE html>';
        $content[] = '<html xmlns="http://www.w3.org/1999/xhtml">';
        $content[] = '<head>';
        $content[] = '<title>'.htmlspecialchars($this->title).'</title>';
        $content[] = $this->getStyleManager()->toString();
        $content[] = $this->getLinkManager()->toString();
        $content[] = $this->getMetaManager()->toString();
        $content[] = $this->getScriptManager()->toString();
        $content[] = '</head>';
        $content[] = ' ';
        $content[] = $layoutContent;
        $content[] = '</html>';
        
        $content = array_filter($content);
        $content = implode("\n", $content);
        
        if ( null !== $this->format ) {
            $content = HtmlFormatter::format($content,$this->format);
        }
        
        $this->layout['content'] = $content;
        return $content;
    }
    
    /**
     * Do render by given information.
     * @return string
     */
    private function renderLayout() {
        if ( is_string($this->layout['view']) && is_file($this->layout['view']) ) {
            extract($this->getDataManager()->toArray(), EXTR_OVERWRITE);
            ob_start();
            ob_implicit_flush(false);
            require $this->layout['view'];
            $this->layout['content'] = ob_get_clean();
        } else if ( is_callable($this->layout['view']) ) {
            $this->layout['content'] = call_user_func_array($this->layout['view'], array($this));
        } else if ( is_string($this->layout['view']) ) {
            $this->layout['content'] = $this->layout['view'];
        } else {
            $this->layout['content'] = '';
        }
        return $this->layout['content'];
    }
    
    /**
     * @param unknown $view
     * @param unknown $data
     * @return string
     */
    public static function renderView( $view, $data=array() ) {
        $content = '';
        if ( is_string($view) && is_file($view) ) {
            $_view = $view;
            extract($data, EXTR_OVERWRITE);
            ob_start();
            ob_implicit_flush(false);
            require $_view;
            $content = ob_get_clean();
        } else if ( is_callable($view) ) {
            $content = call_user_func_array($view, array($data));
        } else if ( is_string($view) ) {
            $content = $view;
        } else {
            $content = '';
        }
        return $content;
    }
    
    /**
     * The title of the page.
     * @var string
     */
    public $title = '';
    
    /**
     * The name of the layout
     * @var array
     */
    protected $layout = array('view'=>null, 'content'=>null);
    
    /**
     * Load layout into this view, You can pass the $name a file path to use 
     * custom layout, or a name to use system layout. 
     * The system layout names are defined by const which starts with LAYOUT_.
     * @param string $name The name of the layout
     */
    public function setLayout( $handler ) {
        $this->layout['view'] = $handler;
    }
    
    /**
     * Display current view.
     * @return null
     */
    public function display() {
        echo $this->toString();
    }
    
    /**
     * Cleanup the content of current view.
     * @return void
     */
    public function cleanUp() {
        $this->layout['content'] = null;
        $this->getParticleViewManager()->cleanUp();
    }
    
    /**
     * @param string $string
     * @return string
     */
    public static function HTMLEncode( $string ) {
        return htmlentities($string, ENT_COMPAT,'UTF-8');
    }
    
    /**
     * @param string $string
     * @return string
     */
    public static function HTMLAttributeEncode($string) {
        return htmlentities($string, ENT_COMPAT,'UTF-8');
    }
    public static function JavascriptValueEncode($string){
        return json_encode($string);
    }
    public static function URLEncode() {}
}