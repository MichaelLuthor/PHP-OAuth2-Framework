<?php
namespace X\Module\SystemManager\Action\Service;
use X\Core\X;
use X\Module\SystemManager\Util\Action;
use X\Module\SystemManager\Util\SystemManagerHelper;

class Import extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Service\SystemManager\Util\Action::run()
     */
    public function run() {
        $error = null;
        if ( isset($_FILES['package']) ) {
            $error = $this->importService();
        }
        
        $servicePositions = array();
        $servicePositions['X'] = 'X框架';
        $moduleNames = X::system()->getModuleManager()->getList();
        foreach ( $moduleNames as $moduleName ) {
            $module = X::system()->getModuleManager()->get($moduleName);
            $servicePositions[$moduleName] = '|- '.$module->getPrettyName().' (模块)';
        }
        
        $this->render('Service/Import', array('error'=>$error, 'position'=>$servicePositions));
    }
    
    /**
     * @return NULL
     */
    private function importService() {
        $serviceName = basename($_FILES['package']['name'], '.zip');
        $serviceManager = X::system()->getServiceManager();
        
        if ( $serviceManager->has($serviceName) ) {
            return 'service already existed.';
        }
        
        $tmpfile = tempnam(sys_get_temp_dir(), 'IMPTMDl');
        move_uploaded_file($_FILES['package']['tmp_name'], $tmpfile);
        
        $position = $this->getParam('position', 'X');
        if ( 'X' === $position ) {
            $serviceClass = 'X\\Service\\'.$serviceName.'\\Service';
            $serviceBasePath = X::system()->getPath('Service');
        } else {
            $module = X::system()->getModuleManager()->get($position);
            $servicePath = $module->getPath('Service/'.$serviceName);
        
            $moduleServicePath = $module->getPath('Service/');
            if ( !is_dir($moduleServicePath) ) {
                mkdir($moduleServicePath);
            }
        
            $serviceNameSpace = get_class($module);
            $serviceNameSpace = substr($serviceNameSpace, 0, strrpos($serviceNameSpace, '\\'));
            $serviceNameSpace = $serviceNameSpace.'\\Service\\'.$serviceName;
            $serviceClass = $serviceNameSpace.'\\Service';
            $serviceBasePath = $module->getPath('Service');
        }
        
        $error = $this->unzipToServicePath($tmpfile, $serviceBasePath);
        if ( null !== $error ) {
            return $error;
        }
        
        try {
            $serviceManager->register($serviceClass);
            return $this->gotoURL('service/detail', array('name'=>$serviceName));
        } catch ( \X\Core\Util\Exception $e ) {
            $error = 'package is not available.';
            $this->deleteUnzipedFiles($tmpfile, $serviceBasePath);
        }
        
        unlink($tmpfile);
        return $error;
    }
    
    private function unzipToServicePath($tmpfile, $serviceBasePath) {
        $zip = zip_open($tmpfile);
        if ( !is_resource($zip) ) {
            return 'package is not a availabel zip file.';
        }
        
        while ( false !== ($entry = zip_read($zip)) ) {
            $name = zip_entry_name($entry);
            $targetPath = $serviceBasePath.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $name);
            if ( file_exists($targetPath) ) {
                return 'service already existed.';
            }
             
            if ( '/' === $name[strlen($name)-1] ) {
                mkdir($targetPath);
            } else {
                $fileContent = zip_entry_read($entry, zip_entry_filesize($entry));
                file_put_contents($targetPath, $fileContent);
            }
            zip_entry_close($entry);
        }
        
        zip_close($zip);
        return null;
    }
    
    private function deleteUnzipedFiles($zip, $serviceBasePath) {
        $zip = zip_open($zip);
        if ( !is_resource($zip) ) {
            return 'package is not a availabel zip file.';
        }
        
        while ( false !== ($entry = zip_read($zip)) ) {
            $name = zip_entry_name($entry);
            $targetPath = $serviceBasePath.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $name);
            if ( file_exists($targetPath) ) {
                SystemManagerHelper::deletePath($targetPath);
            }
            zip_entry_close($entry);
        }
        
        zip_close($zip);
        return null;
    }
}