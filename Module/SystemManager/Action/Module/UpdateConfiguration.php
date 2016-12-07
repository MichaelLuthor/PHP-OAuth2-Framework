<?php
namespace X\Module\SystemManager\Action\Module;
use X\Core\X;
use X\Module\SystemManager\Util\Action;
use X\Module\SystemManager\Util\HttpError404Handler;

class UpdateConfiguration extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $name = $this->getParam('name');
        $module = X::system()->getModuleManager()->get($name);
        
        if ( null === $module ) {
            HttpError404Handler::run();
        }
        
        $config = $this->getParam('module_config_content');
        
        $config = json_decode($config, true);
        if ( null === $config ) {
            return $this->gotoURL('module/detail', array('name'=>$name));
        }
        
        $module->getConfiguration()->removeAll();
        $module->getConfiguration()->setValues($config);
        
        $module->getConfiguration()->save();
        $this->gotoURL('module/detail', array('name'=>$name));
    }
}