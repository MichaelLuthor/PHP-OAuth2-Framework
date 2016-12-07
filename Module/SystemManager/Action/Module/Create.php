<?php
namespace X\Module\SystemManager\Action\Module;
use X\Core\X;
use X\Module\SystemManager\Util\Action;

class Create extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $moduleInfo = $this->getParam('newModule', array());
        $erros = array();
        if ( !empty($moduleInfo) ) {
            $erros = $this->checkModuleInfo($moduleInfo);
            if ( empty($erros) ) {
                $erros['main'] = $this->createModule($moduleInfo);
                if ( null === $erros['main'] ) {
                    return $this->gotoURL('module/detail', array('name'=>$moduleInfo['id']));
                }
            }
        } else {
            $moduleInfo['id'] = '';
            $moduleInfo['name'] = '';
            $moduleInfo['description'] = '';
            $moduleInfo['extends'] = 'X\\Core\\Module\\XModule';
            $moduleInfo['useSpace'] = 'no';
            $moduleInfo['tabSize'] = '';
            $moduleInfo['otherMethods'] = '';
        }
        
        $this->render('Module/Create', array('module'=>$moduleInfo, 'errors'=>$erros));
    }
    
    /**
     * @param unknown $module
     */
    private function checkModuleInfo( $module ) {
        $errors = array();
        if ( empty($module['id']) ) {
            $errors['id'] = 'ID could be empty.';
        }
        
        $moduleManager = X::system()->getModuleManager();
        if ( !empty($module['id']) && $moduleManager->has($module['id']) ) {
            $errors['id'] = 'ID already existed。';
        }
        
        if ( !empty($module['extends']) && !class_exists($module['extends'], true) ) {
            $errors['extends'] = '继承的父类不存在。';
        }
        
        if ( !empty($module['extends']) 
        && class_exists($module['extends'], true)
        && 'X\\Core\\Module\\XModule' !== $module['extends']
        && !is_subclass_of($module['extends'], 'X\\Core\\Module\\XModule') ) {
            $errors['extends'] = '继承的父类不是一个合法的模块类。';
        }
        
        return $errors;
    }
    
    /**
     * @param unknown $module
     * @return string
     */
    private  function createModule( $module ) {
        $modulePath = X::system()->getPath('Module/'.$module['id']);
        if ( is_dir($modulePath) ) {
            return 'module folder already exists.';
        }
        
        if ( false === mkdir($modulePath) ) {
            return 'unable to create module folder.';
        }
        
        $moduleClassPath = $modulePath.DIRECTORY_SEPARATOR.'Module.php';
        $moduleClassContent = $this->createModuleClass($module);
        file_put_contents($moduleClassPath, $moduleClassContent);
        
        $moduleManager = X::system()->getModuleManager();
        $moduleManager->register($module['id']);
        return null;
    }
    
    /**
     * @param unknown $module
     */
    private function createModuleClass( $module ) {
        $tab = "\t";
        if ( 'yes' === $module['useSpace'] ) {
            $tab = array_fill(0, intval($module['tabSize']), ' ');
            $tab = implode('', $tab);
        }
        
        $moduleFileContent = array();
        $moduleFileContent[] = '<?php';
        $moduleFileContent[] = sprintf('namespace X\\Module\\%s;', $module['id']);
        if ( !empty($module['extends']) ) {
            $moduleParentClassName = $module['extends'];
        } else {
            $moduleParentClassName = 'X\\Core\\Module\\XModule';
        }
        
        $moduleFileContent[] = sprintf('use %s;', $moduleParentClassName);
        
        $moduleFileContent[] = '';
        $moduleFileContent[] = '/**';
        $moduleFileContent[] = ' * ';
        $moduleFileContent[] = ' */';
        $moduleParentClassShortName = substr($moduleParentClassName, 1+strrpos($moduleParentClassName, '\\'));
        $moduleFileContent[] = sprintf('class Module extends %s {', $moduleParentClassShortName);
        
        $parentRunMethod = new \ReflectionMethod($moduleParentClassName, 'run');
        if ( $parentRunMethod->isAbstract() ) {
            $moduleFileContent[] = $tab.'/**';
            $moduleFileContent[] = $tab.' * (non-PHPdoc)';
            $moduleFileContent[] = $tab.' * @see \X\Core\Module\XModule::run()';
            $moduleFileContent[] = $tab.' */';
            $moduleFileContent[] = $tab.'public function run($parameters = array()) {';
            $moduleFileContent[] = $tab.$tab.'echo "new module works.";';
            $moduleFileContent[] = $tab.'}';
            $moduleFileContent[] = '';
        }
        
        $module['name'] = trim($module['name']);
        if ( !empty($module['name']) ) {
            $moduleFileContent[] = $tab.'/**';
            $moduleFileContent[] = $tab.' * (non-PHPdoc)';
            $moduleFileContent[] = $tab.' * @see \X\Core\Module\XModule::getPrettyName()';
            $moduleFileContent[] = $tab.' */';
            $moduleFileContent[] = $tab.'public function getPrettyName() {';
            $moduleFileContent[] = $tab.$tab.sprintf('return %s;', var_export($module['name'], true));
            $moduleFileContent[] = $tab.'}';
            $moduleFileContent[] = '';
        }
        
        $module['description'] = trim($module['description']);
        if ( !empty($module['description']) ) {
            $moduleFileContent[] = $tab.'/**';
            $moduleFileContent[] = $tab.' * (non-PHPdoc)';
            $moduleFileContent[] = $tab.' * @see \X\Core\Module\XModule::getDescription()';
            $moduleFileContent[] = $tab.' */';
            $moduleFileContent[] = $tab.'public function getDescription() {';
            $moduleFileContent[] = $tab.$tab.sprintf('return %s;', var_export($module['description'], true));
            $moduleFileContent[] = $tab.'}';
            $moduleFileContent[] = '';
        }
        
        $moduleFileContent[] = $tab.'/**';
        $moduleFileContent[] = $tab.' * (non-PHPdoc)';
        $moduleFileContent[] = $tab.' * @see \X\Core\Module\XModule::getVersion()';
        $moduleFileContent[] = $tab.' */';
        $moduleFileContent[] = $tab.'public function getVersion() {';
        $moduleFileContent[] = $tab.$tab.'return array(0, 0, 1);';
        $moduleFileContent[] = $tab.'}';
        
        $module['otherMethods'] = trim($module['otherMethods']);
        if ( !empty($module['otherMethods']) ) {
            $moduleFileContent[] = '';
            $otherMethods = str_replace("\r", '', $module['otherMethods']);
            $otherMethods = explode("\n", $otherMethods);
            foreach ( $otherMethods as $index => $otherMethod ) {
                $otherMethods[$index] = $tab.$otherMethod;
            }
            $otherMethods = implode("\n", $otherMethods);
            $moduleFileContent[] = $otherMethods;
        }
        
        $moduleFileContent[] = '}';
        return implode("\n", $moduleFileContent);
    }
}