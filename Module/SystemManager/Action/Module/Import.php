<?php
namespace X\Module\SystemManager\Action\Module;
use X\Core\X;
use X\Module\SystemManager\Util\Action;
use X\Module\SystemManager\Util\SystemManagerHelper;

class Import extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $error = null;
        if ( isset($_FILES['package']) ) {
            $error = $this->importModule();
        }
        $this->render('Module/Import', array('error'=>$error));
    }
    
    /**
     * @return NULL
     */
    private function importModule() {
        $moduleName = basename($_FILES['package']['name'], '.zip');
        $moduleManager = X::system()->getModuleManager();
        
        if ( $moduleManager->has($moduleName) ) {
            return 'module already existed.';
        }
        
        $tmpfile = tempnam(sys_get_temp_dir(), 'IMPTMDl');
        move_uploaded_file($_FILES['package']['tmp_name'], $tmpfile);
        
        $error = $this->unzipToModulePath($tmpfile);
        if ( null !== $error ) {
            return $error;
        }
        
        try {
            $moduleManager->register($moduleName);
            return $this->gotoURL('module/detail', array('name'=>$moduleName));
        } catch ( \X\Core\Util\Exception $e ) {
            $error = 'package is not available.';
            $this->deleteUnzipedFiles($tmpfile);
        }
        
        unlink($tmpfile);
        return $error;
    }
    
    private function unzipToModulePath($tmpfile) {
        $moduleBasePath = X::system()->getPath('Module');
        $zip = zip_open($tmpfile);
        if ( !is_resource($zip) ) {
            return 'package is not a availabel zip file.';
        }
        
        while ( false !== ($entry = zip_read($zip)) ) {
            $name = zip_entry_name($entry);
            $targetPath = $moduleBasePath.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $name);
            if ( file_exists($targetPath) ) {
                return 'module already existed.';
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
    
    private function deleteUnzipedFiles($zip) {
        $moduleBasePath = X::system()->getPath('Module');
        $zip = zip_open($zip);
        if ( !is_resource($zip) ) {
            return 'package is not a availabel zip file.';
        }
        
        while ( false !== ($entry = zip_read($zip)) ) {
            $name = zip_entry_name($entry);
            $targetPath = $moduleBasePath.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $name);
            if ( file_exists($targetPath) ) {
                SystemManagerHelper::deletePath($targetPath);
            }
            zip_entry_close($entry);
        }
        
        zip_close($zip);
        return null;
    }
}