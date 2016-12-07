<?php
namespace X\Service\XAction\Management\Action;
use X\Core\X;
use X\Module\SystemManager\Library\ServiceManagementAction;
use X\Service\XAction\Management\Util\ActionBuilder;

class CreateAction extends ServiceManagementAction {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $viewData = array();
        $viewData['moduleName'] = $this->getParam('moduleName', '');
        $viewData['actionName'] = $this->getParam('actionName', '');
        $viewData['actionName'] = $this->getParam('actionName', '');
        $viewData['actionExtend'] = $this->getParam('actionExtend', '');
        $viewData['params'] = $this->getParam('params', array());
        $viewData['status'] = null;
        
        if ( null !== $this->getParam('actionName') && 'true'===$this->getParam('save', 'true')) {
            $viewData['status'] = $this->createAction();
        }
        
        $moduleNames = X::system()->getModuleManager()->getList();
        $modules = array();
        foreach ( $moduleNames as $moduleName ) {
            $modules[$moduleName] = X::system()->getModuleManager()->get($moduleName);
        }
        $viewData['modules'] = $modules;
        
        $this->render('CreateAction', $viewData);
    }
    
    /**
     * @return string|null
     */
    private function createAction() {
        $option = array();
        $option['module'] = $this->getParam('moduleName');
        $option['action'] = $this->getParam('actionName');
        $option['extends'] = $this->getParam('actionExtend');
        $option['params'] = $this->getParam('params', array());
        $builder = new ActionBuilder($option, $this->getModule());
        
        $status = array();
        if ( $builder->build() ) {
            $status = array(
                'success', 
                'Action created : '.$option['action'], 
                $builder->getActionFileContent(), 
                $builder->getActionFilePath()
            );
        } else {
            $status = array('danger', $builder->getErrorMessage());
        }
        return $status;
    }
}