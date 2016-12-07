<?php
namespace X\Module\SystemManager\Action\Module;
use X\Module\SystemManager\Util\Action;
use X\Core\X;

class Index extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $moduleManager = X::system()->getModuleManager();
        
        $moduleNames = $moduleManager->getList();
        $modules = array();
        foreach ( $moduleNames as $moduleName ) {
            $modules[$moduleName] = $moduleManager->get($moduleName);
        }
        
        $this->render('Module/Index', array('modules'=>$modules));
    }
}
