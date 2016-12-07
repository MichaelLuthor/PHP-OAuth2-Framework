<?php
namespace X\Service\XView\Core\Util\HtmlView;
/**
 * 
 */
use X\Core\X;
use X\Core\Util\ConfigurationArray;
use X\Service\XView\Service as XViewService;
/**
 * 
 */
class ParticleView {
    private $name = null;
    private $handler = null;
    private $data = null;
    private $option = null;
    private $content = null;
    private $manager = null;
    private $assetURL = null;
    
    /**
     * @param unknown $handler
     * @param ParticleViewManager $manager
     */
    public function __construct( $name, $handler, ParticleViewManager $manager=null ) {
        $this->name = $name;
        $this->handler = $handler;
        $this->data = new ConfigurationArray();
        $this->option = new ConfigurationArray();
        $this->manager = $manager;
        
        $this->cacheManager = X::system()->getServiceManager()->get(XViewService::getServiceName())->getCacheManager();
    }
    
    /**
     * @return \X\Core\Util\ConfigurationArray
     */
    public function getDataManager() {
        return $this->data;
    }
    
    /**
     * @return \X\Core\Util\ConfigurationArray
     */
    public function getOptionManager() {
        return $this->option;
    }
    
    /**
     * @return \X\Service\XView\Core\Util\HtmlView\ParticleViewManager
     */
    public function getManager() {
        return $this->manager;
    }
    
    /**
     * Displayt the content of particle.
     */
    public function display() {
        echo $this->toString();
    }
    
    /**
     * @return string
     */
    public function toString() {
        if ( null === $this->content ) {
            $this->content = $this->doRender();
        }
        return $this->content;
    }
    
    /**
     * Do render by given information.
     * @param mixed $view The view to render.
     * @param mixed $data The data that view would used.
     * @return string
     */
    private function doRender() {
        if ( is_string($this->handler) && is_file($this->handler) ) {
            extract($this->getViewRenderData());
            ob_start();
            ob_implicit_flush(false);
            require $this->handler;
            return ob_get_clean();
        } else if ( is_callable($this->handler) ) {
            return call_user_func_array($this->handler, array($this->getViewRenderData(), $this->getOptionManager()->toArray()));
        } else if ( is_string($this->handler) ) {
            return $this->handler;
        } else {
            return null;
        }
    }
    
    /**
     * @return multitype:
     */
    private function getViewRenderData(){
        $pageData = array();
        if ( null !== $this->manager ) {
            $pageData = $this->manager->getHost()->getDataManager()->toArray();
        }
        return array_merge($pageData,$this->getDataManager()->toArray());
    }
    
    /**
     * 
     */
    public function cleanUp() {
        $this->content = null;
    }
    
    /**
     * @param unknown $path
     * @return string
     */
    public function getAsset( $path, $module ) {
        $module = X::system()->getModuleManager()->get($module);
        return $module->getAssetsLink($path);
    }
    
    /**
     * @param unknown $name
     * @param unknown $module
     * @param unknown $path
     */
    public function addJS( $name, $module, $path ) {
        if ( null !== $module ) {
            $path = $this->getAsset($path, $module);
            $this->getManager()->getHost()->getScriptManager()->add($name)->setSource($path);
        } else {
            $this->getManager()->getHost()->getScriptManager()->add($name)->setSource($path);
        }
    }
    
    /**
     * @param unknown $name
     * @param unknown $content
     */
    public function addJSString( $name, $content ) {
        $this->getManager()->getHost()->getScriptManager()->add($name)->setContent($content);
    }
    
    /**
     * @param unknown $name
     * @param unknown $module
     * @param unknown $path
     */
    public function addCSS( $name, $module, $path ) {
        $path = $this->getAsset($path, $module);
        $this->getManager()->getHost()->getLinkManager()->addCSS($name, $path);
    }
    
    /**
     * @var \X\Service\XView\Core\Cache\Manager
     */
    private $cacheManager = null;
    private $cacheMark = null;
    
    /**
     * @param unknown $mark
     */
    public function setCacheMark($mark) {
        $this->cacheMark = $mark;
    }
    
    /**
     * @param unknown $mark
     * @return boolean
     */
    public function loadCache() {
        $content = $this->cacheManager->getContent($this->name, $this->cacheMark);
        if ( null === $content || false===$content ) {
            return false;
        }
        $this->content = $content;
        return true;
    }
    
    /**
     * @param unknown $lifetime
     */
    public function cache($lifetime=null) {
        $this->cacheManager->cacheContent(
            $this->name, 
            $this->toString(), 
            $this->cacheMark, 
            $lifetime
        );
    }
    
    /**
     * @return void
     */
    public function cleanCache() {
        $this->cacheManager->clean($this->name, $this->cacheMark);
    }
}