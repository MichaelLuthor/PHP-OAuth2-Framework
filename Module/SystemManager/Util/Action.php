<?php
namespace X\Module\SystemManager\Util;
use X\Core\X;

abstract class Action {
    abstract public function run();
    
    /**
     * @var \X\Module\SystemManager\Module 
     */
    private $module = null;
    
    private $parameters = array();
    
    protected $layoutHeader = array();
    
    public function __construct( $module, $params ) {
        $this->module = $module;
        $this->parameters = $params;
        
        if ( $this->isLoginRequired() && $this->isGuestUser()) {
            $this->gotoURL('login');
        }
    }
    
    /**
     * @return boolean
     */
    protected function isGuestUser() {
        return !isset($_SESSION['system_manager_user_account']);
    }
    
    /**
     * @param unknown $account
     * @param unknown $password
     */
    protected function login( $account, $password ) {
        $accounts = $this->getModule()->getConfiguration()->get('manager_accounts', array());
        if ( !isset($accounts[$account]) ) {
            return false;
        }
        
        if ( $accounts[$account]['password'] !== $password ) {
            return false;
        }
        
        $_SESSION['system_manager_user_account'] = $account;
        return true;
    }
    
    public function getParam( $name, $default=null ) {
        $value = $default;
        if ( isset($this->parameters[$name]) ){
            $value = $this->parameters[$name];
        }
        return $value;
    }
    
    /**
     * @return boolean
     */
    protected function isLoginRequired() {
        return true;
    }
    
    /**
     * @return array
     */
    public function getAllParams() {
        return $this->parameters;
    }
    
    public function getModule() {
        return $this->module;
    }
    
    public function render( $name, $data=array() ) {
        $content = $this->renderView($name, $data);
        
        $layoutPath = $this->getModule()->getPath('View/Layout/Default.php');
        $layoutData = array('content'=>$content, 'head'=>$this->layoutHeader);
        echo $this->renderView($layoutPath, $layoutData);
    }
    
    protected function renderView( $path, $data ) {
        if ( !is_file($path) ) {
            $path = $this->getModule()->getPath('View/'.$path.'.php');
        }
        
        ob_start();
        ob_implicit_flush(true);
        extract($data);
        require $path;
        return ob_get_clean();
    }
    
    public function createURL($action, $params=array()) {
        $params = array_merge(array('module'=>'systemManager', 'action'=>$action), $params);
        return 'index.php?'.http_build_query($params);
    }
    
    public function encodeHtmlString( $string ) {
        return htmlspecialchars($string);
    }
    
    public function addHeaderItem( $header ) {
        $this->layoutHeader[] = $header;
    }
    
    public function gotoURL( $action, $params=array() ) {
        header("Location: ".$this->createURL($action, $params)); 
        X::system()->stop();
    }
    
    public function goBack() {
        header("Location: {$_SERVER['HTTP_REFERER']}");
        X::system()->stop();
    }
    
    public function isPost() {
        return 'POST'===$_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * @param unknown $name
     * @return \X\Core\Service\XService
     */
    public function getModuleByName( $name ) {
        return X::system()->getModuleManager()->get($name);
    }
    
    /**
     * @param unknown $name
     * @return \X\Core\Service\XService
     */
    public function getServiceByName( $name ) {
        return X::system()->getServiceManager()->get($name);
    }
}