<?php
namespace X\Service\XAction\Management\Util;
use X\Core\X;
use X\Module\SystemManager\Util\SystemManagerHelper;

class ActionBuilder {
    /**
     * @var unknown
     */
    private $option = array();
    
    /**
     * @var unknown
     */
    private $managerModule = null;
    
    /**
     * 
     * 
     * @param array $option
     * <pre>
     * module - the name of module that action belongs.
     * action - the name of the action.
     * extends - the name of parent class.
     * params - the parameter list. example:
     *          array(array('name'=>'param001', 'comment'=>'comment content'), ...)
     * </pre>
     */
    public function __construct($option, $managerModule) {
        $this->option = $option;
        $this->managerModule = $managerModule;
    }
    
    /**
     * @var unknown
     */
    private $errorMessage = null;
    
    /**
     * @return string
     */
    public function getErrorMessage() {
        return $this->errorMessage;
    }
    
    /**
     * @var unknown
     */
    private $path = null;
    
    /**
     * @return string
     */
    public function getActionFilePath() {
        return $this->path;
    }
    
    /**
     * @var unknown
     */
    private $content = null;
    
    /**
     * @return string
     */
    public function getActionFileContent() {
        return $this->content;
    }
    
    /**
     * @return boolean
     */
    public function build() {
        $moduleName = isset($this->option['module']) ? $this->option['module'] : null;
        if ( empty($moduleName) ) {
            return $this->error('target module could not be empty.');
        }
        
        if ( $this->managerModule->getName() === $moduleName ) {
            return $this->error('target module could not be system management module.');
        }
        
        $module = X::system()->getModuleManager()->get($moduleName);
        if ( null === $module ) {
            return $this->error('target module does not exists.');
        }
        
        $name = isset($this->option['action']) ? $this->option['action'] : null;
        if ( empty($name) ) {
            return $this->error('action name could not be empty.');
        }
        
        $actionExtend = isset($this->option['extends']) ? $this->option['extends'] : null;
        if ( empty($actionExtend) ) {
            return $this->error('extended class could not be empty.');
        }
        
        if ( ('X\\Service\\XAction\\Core\\Handler\\WebAction'!==$actionExtend
            && 'X\\Service\\XAction\\Core\\Handler\\CommandAction')
            && ( !is_subclass_of($actionExtend, 'X\\Service\\XAction\\Core\\Handler\\WebAction')
                && !is_subclass_of($actionExtend, 'X\\Service\\XAction\\Core\\Handler\\CommandAction') )
        ) {
            return $this->error('extended class is not available.');
        }
        
        return $this->createActionFile();
    }
    
    /**
     * @return boolean
     */
    private function createActionFile() {
        $content = array();
        $tab = '    ';
    
        $module = $this->option['module'];
        $module = X::system()->getModuleManager()->get($module);
        $namespace = SystemManagerHelper::getClassNamespaceName($module);
        $namespace .= '\\Action';
    
        $name = $this->option['action'];
        $name = array_map('ucfirst', explode('/', $name));
        $actionPath = $module->getPath('Action/'.implode('/', $name).'.php');
        if ( file_exists($actionPath) ) {
            return $this->error('Action file already exists.');
        }
        
        $actionName = array_pop($name);
        $name = implode('\\', $name);
        if ( !empty($name) ) {
            $namespace .= ('\\'.$name);
        }
        $content[] = sprintf('namespace %s;', $namespace);
    
        $actionExtend = $this->option['extends'];
        $content[] = sprintf('use %s;', $actionExtend);
        $content[] = '';
        $content[] = sprintf('class %s extends %s {', $actionName, substr($actionExtend, strripos($actionExtend, '\\')+1));
    
        $params = $this->option['params'];
        $paramList = array();
        foreach ( $params as $param ) {
            $paramList[] = '$'.$param['name'];
        }
        $paramList = implode(', ', $paramList);
    
        $content[] = $tab.'/**';
        $content[] = $tab.' * This action is generated by XAxtion service.';
        $content[] = $tab.' *';
        foreach ( $params as $param ) {
            $content[] = $tab.sprintf(' * @param unknown $%s %s', $param['name'], $param['comment']);
        }
        $content[] = $tab.' * @see \X\Service\XAction\Core\Util\Action::runAction()';
        $content[] = $tab.' */';
        $content[] = $tab.sprintf('public function runAction( %s ) {', $paramList);
        $content[] = $tab.$tab.'echo \'new action works!\';';
        $content[] = $tab.'}';
        $content[] = '}';
        $content = implode("\n", $content);
        
        $this->content = $content;
        $fileContent = "<?php \n".$content;
        SystemManagerHelper::createFolder(substr($actionPath, 0, strripos($actionPath, DIRECTORY_SEPARATOR)));
        file_put_contents($actionPath, $fileContent);
        $this->path = $actionPath;
        
        return true;
    }
    
    /**
     * @return boolean
     */
    private function error( $message ) {
        $this->errorMessage = $message;
        return false;
    }
    
    /**
     * @return boolean
     */
    private function success() {
        return true;
    }
}