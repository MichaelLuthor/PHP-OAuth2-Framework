<?php
/**
 * Namespace defination
 */
namespace X\Service\XView;
/**
 * 
 */
use X\Core\X;
use X\Service\XView\Core\Util\Exception;
use X\Service\XView\Core\Cache\Manager as CacheManager;
use X\Module\SystemManager\Library\SystemManagerEvent;
/**
 * The view service
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @since   0.0.0
 * @version 0.0.0
 */
class Service extends \X\Core\Service\XService {
    /**
     * 
     */
    protected static $serviceName = 'XView';
    
    /**
     * {@inheritDoc}
     * @see \X\Core\Service\XService::start()
     */
    public function start() {
        parent::start();
        X::system()->getEventManager()->registerHandler(
            SystemManagerEvent::SOURCE_UPDATE, 
            array($this->getCacheManager(), 'cleanAll')
        );
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::stop()
     */
    public function stop() {
        if ( $this->getConfiguration()->get('auto_display', false) ) {
            $this->display();
        }
        $this->views = array();
        $this->activedView = null;
        parent::stop();
    }
    
    private $cacheManager = null;
    
    /**
     * @return \X\Service\XView\Core\Cache\Manager
     */
    public function getCacheManager() {
        return (null === $this->cacheManager) 
        ? ($this->cacheManager = new CacheManager())
        : $this->cacheManager;
    }
    
    /**
     * This value contains all loaded view instance.
     * -- key : {string} the name of view
     * -- value: {X\Service\XView\View} the instance of view handler.
     * @var X\Service\XView\View[]
     */
    protected $views = array(
    /* 'view1' => $viewObje, */
    );
    
    /**
     * Create a new view handler and load it into view service.
     * @param string $name The name of the view.
     * @param string $type The type of the view, the value of it could be a name of view
     *                     handler class or the const values of XViewService::VIEW_TYPE_*
     * @return \X\Service\XView\Service
     */
    private function create( $name, $type ) {
        if ( $this->has($name) ) {
            throw new Exception('View handler "'.$name.'" already exists.');
        }
        
        $viewHandler = '\\X\\Service\\XView\\Core\\Handler\\'.$type;
        $view = new $viewHandler();
        if ( !($view instanceof \X\Service\XView\Core\Util\View) ) {
            throw new Exception('"'.$viewHandler.'" is not a valid view handler.');
        }
        $this->views[$name] = $view;
        return $view;
    }
    
    /**
     * @param unknown $name
     * @return \X\Service\XView\Core\Handler\Html
     */
    public function  createHtml($name) {
        return $this->create($name, 'Html');
    }
    
    /**
     * Check the view by given name. return  true if the view exists or false if not.
     * @param string $name The name of view
     * @return boolean
     */
    public function has( $name ) {
        return isset($this->views[$name]);
    }
    
    /**
     * Get view instance by given name.
     * @param string $name The name of view.
     * @return X\Service\XView\View
     */
    public function get($name){
        if ( !$this->has($name) ) {
            throw new Exception('View "'.$name.'" does not exists.');
        }
        return $this->views[$name];
    }
    
    /**
     * Get the name list of views.
     * @return \X\View\XView
     */
    public function getList(){
        return array_keys($this->views);
    }
    
    /**
     * @param unknown $name
     */
    public function deleteView( $name ) {
        if ( $this->has($name) ) {
            unset($this->views[$name]);
        }
    }
    
    /**
     * @return void
     */
    public function deleteAllViews() {
        $this->views = array();
    }
    
    /**
     * The name of active view.
     * @var string
     */
    protected $activedView = null;
    
    /**
     * Set given view to active.
     * @param string $name The name of view to active.
     * @return void
     */
    public function activeView($name){
        $this->activedView = $name;
    }
       
    /**
     * Display the active view. If there is no active view, then
     * nothing would be displayed.
     * @return void
     */
    public function display(){
        if ( null === $this->activedView ) {
            return;
        }
        $this->log('display view "%s"', $this->activedView);
        $this->views[$this->activedView]->display();
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::getPrettyName()
     */
    public function getPrettyName() {
        return '视图管理服务';
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::getDescription()
     */
    public function getDescription() {
        return '管理和渲染视图文件。';
    }
}