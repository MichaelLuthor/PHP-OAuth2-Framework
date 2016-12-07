<?php
namespace X\Module\SystemManager\Library\Test\TestTrait;
use X\Core\X;

trait SystemManagerTestTrait {
    /**
     * @return void
     */
    public function startXFramework() {
        if ( !X::isRunning() ) {
            X::start();
            X::system()->getServiceManager()->start();
            X::system()->getModuleManager()->start();
        }
    }
    
    /**
     * @return void
     */
    public function stopXFramework() {
        X::system()->stop(false);
    }
    
    /**
     * @param string $name
     * @return \X\Core\Service\XService
     */
    public function getService( $name ) {
        return X::system()->getServiceManager()->get($name);
    }
    
    /**
     * @param string $name
     * @return \X\Core\Module\XModule
     */
    public function getModule( $name ) {
        return X::system()->getModuleManager()->get($name);
    }
    
    /**
     * @param unknown $particle
     * @param unknown $string
     */
    public function assertStringContains($particle, $string){
        return $this->assertTrue(false !== strpos($string, $particle));
    }
    
    /**
     * @param unknown $particle
     * @param unknown $string
     */
    public function assertStringNotContains($particle, $string){
        return $this->assertTrue(false === strpos($string, $particle));
    }
}