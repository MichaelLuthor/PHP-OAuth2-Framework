<?php
namespace X\Core\Module;
use X\Core\X;
use X\Core\Util\XUtil;
use X\Core\Util\ConfigurationFile;

/**
 * 
 */
abstract class XModule {
    /**
     * @param array $parameters
     */
    abstract public function run($parameters=array());
    
    /**
     * 
     */
    public function __construct() {
        $this->onLoaded();
    }
    
    /**
     * @return boolean
     */
    protected function onLoaded() {
        return true;
    }
    
    /**
     * @return null
     */
    public function afterLoaded() {
        return null;
    }
    
    /**
     * @return string
     */
    public function getName() {
        $className = get_class($this);
        $className = explode('\\', $className);
        return $className[count($className)-2];
    }
    
    /**
     * @return string
     */
    public static function getModuleName() {
        $className = get_called_class();
        $className = explode('\\', $className);
        return $className[count($className)-2];
    }
    
    /**
     * @param string $path
     * @return string
     */
    public function getPath( $path=null ) {
        return XUtil::getPathRelatedClass($this, $path);
    }
    
    /**
     * @var unknown
     */
    private $configurations = array();
    
    /**
     * @return \X\Core\Util\ConfigurationFile
     */
    public function getConfiguration( $name='main' ) {
        $name = ucfirst($name);
        if ( !isset($this->configurations[$name]) ) {
            $configPath = $this->getPath('Configuration/'.$name.'.php');
            $this->configurations[$name] = new ConfigurationFile($configPath);
        }
        return $this->configurations[$name];
    }
    
    /**
     * @return \X\Core\Util\ConfigurationFile
     */
    private function getManagerConfiguration() {
        return X::system()->getModuleManager()->getConfiguration();
    }
    
    /**
     * @param unknown $item
     * @param unknown $value
     */
    private function updateManagerConfiguration( $item, $value ){
        $configuration = $this->getManagerConfiguration();
        $configuration[$this->getName()][$item] = $value;
        $configuration->save();
    }
    
    /**
     * @param unknown $item
     * @param mixed $default
     */
    private function getManagerConfigurationValue($item, $default=null) {
        $configuration = $this->getManagerConfiguration();
        return isset($configuration[$this->getName()][$item]) ? $configuration[$this->getName()][$item] : $default;
    }
    
    /**
     * @return void
     */
    public function setAsDefault(){
        $this->updateManagerConfiguration('default', true);
    }
    
    /**
     * @return void
     */
    public function unsetAsDefault(){
        $this->updateManagerConfiguration('default', false);
    }
    
    
    /**
     * @return boolean
     */
    public function isDefaultModule(){
        return true===$this->getManagerConfigurationValue('default', false);
    }
    
    /**
     * @return void
     */
    public function enable() {
        $this->onEnabled();
        $this->updateManagerConfiguration('enable', true);
    }
    
    /**
     * @return void
     */
    public function disable(){
        $this->onDisabled();
        $this->updateManagerConfiguration('enable', false);
    }
    
    /**
     * @return mixed
     */
    public function isEnabled(){
        return $this->getManagerConfigurationValue('enable');
    }
    
    /**
     * @return string
     */
    public function getPrettyName() {
        return $this->getName();
    }
    
    /**
     * @return string
     */
    public function getDescription() {
        return '';
    }
    
    /**
     * @return array
     */
    public function getVersion() {
        return array(0,0,0);
    }
    
    /**
     * @return NULL
     */
    protected function onEnabled() {
        return null;
    }
    
    /**
     * @return NULL
     */
    protected function onDisabled() {
        return null;
    }
    
    /**
     * @return string
     */
    public function getAssetsPath() {
        return X::system()->getPath('Assets/'.$this->getName());
    }
    
    /**
     * @return string
     */
    public function getAssetsLink( $assets ) {
        return 'Assets/'.$this->getName().'/'.$assets;
    }
    
    /**
     * @return void
     */
    public function publishAssets() {
        $fromPath = $this->getPath('Assets/');
        if ( !is_dir($fromPath) ) {
            return;
        }
        
        $targetPath = $this->getAssetsPath();
        if ( !is_dir($targetPath) ) {
            mkdir($targetPath, 0777, true);
        }
        
        XUtil::copyPath($fromPath, $targetPath);
    }
    
    /**
     * @return void
     */
    public function cleanAssets() {
        $path = $this->getAssetsPath();
        if ( is_dir($path) ) {
            XUtil::deleteFile($path);
        }
    }
    
    /**
     * @return string
     */
    public function getAssetsLastPublishTime() {
        $path = $this->getAssetsPath();
        if ( is_dir($path) ) {
            return filemtime($path);
        }
        return null;
    }
}