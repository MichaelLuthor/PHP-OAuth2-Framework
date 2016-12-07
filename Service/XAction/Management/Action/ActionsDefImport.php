<?php
namespace X\Service\XAction\Management\Action;
use X\Module\SystemManager\Library\ServiceManagementAction;
use X\Service\XAction\Management\Util\ActionBuilder;

class ActionsDefImport extends ServiceManagementAction {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $status = null;
        if ( isset($_FILES['file']) ) {
            $status = $this->doImport();
        }
        
        $this->render('ActionsDefImport', array('status'=>$status));
    }
    
    /**
     * @return string
     */
    private function doImport() {
        $temfile = tempnam(sys_get_temp_dir(), 'ACTIMPRT');
        if ( UPLOAD_ERR_OK !== $_FILES['file']['error'] ) {
            return array('danger', 'file upload failed.');
        }
        
        move_uploaded_file($_FILES['file']['tmp_name'], $temfile);
        
        $file = fopen($temfile, 'r');
        fgetcsv($file); // drop header
        
        $createdActions = array();
        $lastModuleName = '';
        $lastParentClassName = '';
        $lineNumber = 1;
        $managerModule = $this->getModule();
        while ( false !== ($data=fgetcsv($file)) ) {
            $lineNumber ++ ;
            $moduleName = $data[0];
            if ( empty( $moduleName ) ) {
                $moduleName = $lastModuleName;
            }
            if ( empty($moduleName) ) {
                $this->cleanImportedActions($createdActions);
                return array('danger', 'Line #'.$lineNumber.': module name is required.');
            }
            
            $actionName = $data[1];
            if ( empty($actionName) ) {
                $this->cleanImportedActions($createdActions);
                return array('danger', 'Line #'.$lineNumber.': action name is required.');
            }
            
            $parentClass = $data[2];
            if ( empty($parentClass) ) {
                $parentClass = $lastParentClassName;
            }
            if ( empty($parentClass) ) {
                $this->cleanImportedActions($createdActions);
                return array('danger', 'Line #'.$lineNumber.': extends class name is required.');
            }
            
            $params = $data[3];
            $paramList = array();
            if ( !empty($params) ) {
                $params = explode(';', $params);
                foreach ( $params as $param ) {
                    list($pname, $pcomment) = explode(':', $param);
                    $paramList[] = array('name'=>$pname, 'comment'=>$pcomment);
                }
            }
            
            $option = array(
                    'module'=>$moduleName,
                    'action'=>$actionName,
                    'extends'=>$parentClass,
                    'params'=>$paramList,
            );
            $creator = new ActionBuilder($option, $managerModule);
            if ( $creator->build() ) {
                $createdActions[$moduleName.':'.$actionName] = $creator->getActionFilePath();
            } else {
                $this->cleanImportedActions($createdActions);
                return array('danger', 'Line #'.$lineNumber.': '.$creator->getErrorMessage());
            }
            
            $lastModuleName = $moduleName;
            $lastParentClassName = $parentClass;
        }
        
        return array('success', ($lineNumber-1).' actions were genereated successfully.', $createdActions);
    }
    
    private function cleanImportedActions( $actions ) {
        foreach ( $actions as $action ) {
            unlink($action);
        }
    }
}