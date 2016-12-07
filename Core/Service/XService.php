<?php
/**
 * 
 */
namespace X\Core\Service;

/**
 * 
 */
use X\Core\X;
use X\Core\Util\ConfigurationFile;
use X\Core\Util\XUtil;

/**
 *
 */
abstract class XService {
    /**
     * 该变量保存着所有已经启动的服务的实例。
     * @var \X\Core\Service\XService[]
     */
    private static $services = array();
    
    /**
     * 获取该服务的实例
     * @return \X\Core\Service\XService
     */
    static public function getService() {
        $className = get_called_class();
        if ( !isset(self::$services[$className]) ) {
            self::$services[$className] = new $className();
        }
    
        return self::$services[$className];
    }
    
    /**
     * @var string
     */
    protected static $serviceName = null;
    
    /**
     * 从服务的类名中获取服务名称。
     * @param string $className 要获取服务名称的类名
     * @return string
     */
    private static function getServiceNameFromClassName( $className ) {
        return static::$serviceName;
    }
    
    /**
     * 静态方法获取服务名称
     * @return string
     */
    public static function getServiceName() {
        return self::getServiceNameFromClassName(get_called_class());
    }
    
    /**
     * 非静态方法获取服务名称
     * @return string
     */
    public function getName() {
        return self::getServiceNameFromClassName(get_class($this));
    }
    
    /**
     * 将构造函数保护起来以禁止从其他地方实例化服务。
     * @return void
     */
    protected function __construct() {
        $this->onLoaded();
    }
    
    /**
     * @return NULL
     */
    protected function onLoaded() {
        return null;
    }
    
    /**
     * @var callback
     */
    private $logHandler = null;
    
    /**
     * 启动服务，该方法由管理器启动， 不建议在其他地方调用该方法。
     * @return void
     */
    public function start(){
        $this->status = self::STATUS_RUNNING;
        $this->setupLogHandler();
    }
    
    /**
     * 结束服务，该方法由管理器结束， 不建议在其他地方调用该方法。
     *  @return void
     */
    public function stop(){ 
        $this->status = self::STATUS_STOPPED;
    }
    
    /**
     * @return null
     */
    protected function setupLogHandler() {
        $handler = $this->getConfiguration()->get('logger', false);
        if ( false === $handler ) {
            return;
        }
        if ( !$handler['enable'] ) {
            return;
        }
        switch ( $handler['handler'] ) {
        case 'service' : 
            $service = X::system()->getServiceManager()->get($handler['name']);
            $this->logHandler = array($service, $handler['method']);
            break;
        default:
            break;
        }
    }
    
    /**
     * @param unknown $message
     */
    public function log($message) {
        if ( null === $this->logHandler ) {
            return;
        }
        $message = call_user_func_array('sprintf', func_get_args());
        call_user_func_array($this->logHandler, array($this, $message));
    }
    
    /**
     * 
     */
    public function destroy() {
        $className = get_called_class();
        unset(self::$services[$className]);
    }
    
    /**
     * 获取当前服务下的文件或目录的绝对路径。
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
     * @var integer
     */
    const STATUS_STOPPED = 0;
    
    /**
     * @var integer
     */
    const STATUS_RUNNING = 1;
    
    /**
     * @var status
     */
    private $status = self::STATUS_STOPPED;
    
    /**
     * @return integer
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * @return \X\Core\Util\ConfigurationFile
     */
    private function getManagerConfiguration() {
        return X::system()->getServiceManager()->getConfiguration();
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
    public function enable(){
        $this->updateManagerConfiguration('enable', true);
    }
    
    /**
     * @return void
     */
    public function disable(){
        $this->updateManagerConfiguration('enable', false);
    }
    
    /**
     * @return boolean
     */
    public function isEnabled(){
        return $this->getManagerConfigurationValue('enable');
    }
    
    /**
     * @return void
     */
    public function enableLazyLoad(){
        $this->updateManagerConfiguration('delay', true);
    }
    
    /**
     * @return void
     */
    public function disableLazyLoad(){
        $this->updateManagerConfiguration('delay', false);
    }
    
    /**
     * @return boolean
     */
    public function isLazyLoadEnabled(){
        return $this->getManagerConfigurationValue('delay', true);
    }
    
    /**
     * @return string
     */
    public function getPrettyName(){
        return $this->getName();
    }
    
    /**
     * @return string
     */
    public function getDescription(){
        return '';
    }
    
    /**
     * @return multitype:number
     */
    public function getVersion() {
        return array(0,0,0);
    }
    
    /**
     * @return string
     */
    public static function getClassName() {
        return get_called_class();
    }
}