<?php
/**
 * 
 */
namespace X\Core\Module;

/**
 * 
 */
use X\Core\X;
use X\Core\Util\Manager as UtilManager;
use X\Core\Util\Exception;

/**
 * 
 */
class Manager extends UtilManager {
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\Manager::getConfigurationFilePath()
     */
    protected function getConfigurationFilePath() {
        return X::system()->getPath('Core/Module/Configuration/Main.php');
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\Manager::start()
     */
    public function start() {
        parent::start();
        
        /* setup default module name */
        foreach ( $this->getConfiguration() as $name => $config ) {
            if ( isset($config['default']) && true===$config['default'] ) {
                $this->defaultModuleName = $name;
            }
            $this->load($name);
        }
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Util\Manager::stop()
     */
    public function stop() {
        $this->loadedModules = array();
        $this->defaultModuleName = null;
        parent::stop();
    }
    
    /**
     * @var string
     */
    protected $defaultModuleName = null;
    
    /**
     * @throws Exception
     */
    public function run( $name=null ) {
        $parameters = X::system()->getParameter()->toArray();
        $moduleName = isset($parameters['module']) ? $parameters['module'] : $this->defaultModuleName;
        $moduleName = (null === $name) ? $moduleName : $name;
        if ( null === $moduleName ) {
            throw new Exception('Can not find any module to execute.');
        }
        $moduleName = ucfirst($moduleName);
        unset($parameters['module']);
        
        $module = $this->load($moduleName);
        if ( !$module->isEnabled() ) {
            throw new Exception('target module is disabled.');
        }
        return $module->run($parameters);
    }
    
    /**
     * @var array
     */
    private $loadedModules = array();
    
    /**
     * @param unknown $name
     * @throws Exception
     * @return boolean
     */
    protected function load($name) {
        if ( !$this->has($name) ) {
            throw new Exception("Module '$name' can not be found.");
        }
        
        if ( isset($this->loadedModules[$name]) ) {
            return $this->loadedModules[$name];
        }
        
        $moduleClass = 'X\\Module\\'.$name.'\\Module';
        if ( !class_exists($moduleClass) ) {
            throw new Exception("Module handler '$moduleClass' can not be found.");
        }
        if ( !is_subclass_of($moduleClass, '\\X\\Core\\Module\\XModule') ) {
            throw new Exception("Module '$name' is not a available module.");
        }
        $module = new $moduleClass();
        $this->loadedModules[$name] = $module;
        $module->afterLoaded();
        return $module;
    }
    
    /**
     * @return array
     */
    public function getList() {
        return array_keys($this->getConfiguration()->toArray());
    }
    
    /**
     * @param unknown $moduleName
     */
    public function has( $name ) {
        return $this->getConfiguration()->has($name);
    }
    
    /**
     * @param unknown $name
     * @throws Exception
     * @return \X\Core\Module\XModule
     */
    public function get( $name ) {
        return $this->load($name);
    }
    
    /**
     * @param unknown $name
     */
    public function register($name) {
        if ( $this->has($name) ) {
            throw new Exception("Module '$name' alerady exists.");
        }
        
        $moduleClass = 'X\\Module\\'.ucfirst($name).'\\Module';
        if ( !class_exists($moduleClass) ) {
            throw new Exception("Module class '$moduleClass' does not exists.");
        }
        
        $config = array('enable'=>false);
        $this->configuration[$name]=$config;
        $this->configuration->save();
    }
    
    /**
     * @param unknown $name
     * @throws Exception
     */
    public function unregister($name) {
        if ( !$this->has($name) ) {
            throw new Exception("Module '$name' can not be found.");
        }
        $this->getConfiguration()->remove($name);
        $this->getConfiguration()->save();
    }
}