<?php
namespace X\Module\SystemManager\Action\Service;
use X\Core\X;
use X\Module\SystemManager\Util\Action;
/**
 * 
 */
class Create extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $viewData = array();
        $viewData['service'] = array(
            'id' => '',
            'name' => '',
            'description' => '',
            'extends' => 'X\\Core\\Service\\XService',
            'otherMethods' => '',
            'useSpace' => 'yes',
            'tabSize'=>4,
            'position'=>'X',
        );
        $viewData['errors'] = array();
        
        $newService = $this->getParam('newService');
        if ( null !== $newService ) {
            $viewData['service'] = $newService;
            $viewData['errors'] = $this->checkServiceInfo($newService);
            if ( empty($viewData['errors']) ) {
                $viewData['errors']['main'] = $this->createService($newService);
                if ( null === $viewData['errors']['main'] ) {
                    return $this->gotoURL('service/detail', array('name'=>$newService['id']));
                }
            }
        }
        
        $servicePositions = array();
        $servicePositions['X'] = 'X框架';
        $moduleNames = X::system()->getModuleManager()->getList();
        foreach ( $moduleNames as $moduleName ) {
            $module = X::system()->getModuleManager()->get($moduleName);
            $servicePositions[$moduleName] = '|- '.$module->getPrettyName().' (模块)';
        }
        $viewData['servicePosition'] = $servicePositions;
        
        $this->render('Service/Create', $viewData);
    }
    
    private function checkServiceInfo( $service ) {
        $serviceManager = X::system()->getServiceManager();
        
        $errors = array();
        if ( empty($service['id']) ) {
            $errors['id'] = 'Service id could not be empty.';
        }
        
        if ( !empty($service['id']) && $serviceManager->has($service['id']) ) {
            $errors['id'] = 'Service already existsed.';
        }
        
        if ( 'X' !== $service['position'] 
        && !X::system()->getModuleManager()->has($service['position'])) {
            $errors['position'] = 'position is not available.';
        }
        
        if ( !empty($service['extends']) 
        && ('X\\Core\\Service\\XService' !== $service['extends'] )
        && ( !class_exists($service['extends'], true) 
            || !is_subclass_of($service['extends'], 'X\\Core\\Service\\XService') )
        ) {
            $errors['extends'] = 'extends class is not available.';
        }
        
        return $errors;
    }
    
    /**
     * @param unknown $service
     */
    private function createService( $service ) {
        if ( 'X' === $service['position'] ) {
            $servicePath = X::system()->getPath('Service/'.$service['id']);
            $serviceClass = 'X\\Service\\'.$service['id'].'\\Service';
        } else {
            $module = X::system()->getModuleManager()->get($service['position']);
            $servicePath = $module->getPath('Service/'.$service['id']);
            
            $moduleServicePath = $module->getPath('Service/');
            if ( !is_dir($moduleServicePath) ) {
                mkdir($moduleServicePath);
            }
            
            $serviceNameSpace = get_class($module);
            $serviceNameSpace = substr($serviceNameSpace, 0, strrpos($serviceNameSpace, '\\'));
            $serviceNameSpace = $serviceNameSpace.'\\Service\\'.$service['id'];
            $serviceClass = $serviceNameSpace.'\\Service';
        }
        if ( is_dir($servicePath) ) {
            return 'service folder already exists.';
        }
        
        if ( false === mkdir($servicePath) ) {
            return 'unable to create service folder.';
        }
        
        $serviceClassPath = $servicePath.DIRECTORY_SEPARATOR.'Service.php';
        $serviceFileContent = $this->createServiceFile($service);
        file_put_contents($serviceClassPath, $serviceFileContent);
        
        X::system()->getServiceManager()->register($serviceClass);
        return null;
    }
    
    /**
     * @param unknown $service
     */
    private function createServiceFile( $service ) {
        $tab = "\t";
        if ( 'yes' === $service['useSpace'] ) {
            $tab = array_fill(0, intval($service['tabSize']), ' ');
            $tab = implode('', $tab);
        }
        
        $serviceFileContent = array();
        $serviceFileContent[] = '<?php';
        
        if ( 'X' === $service['position'] ) {
            $serviceFileContent[] = sprintf('namespace X\\Service\\%s;', $service['id']);
        } else {
            $module = X::system()->getModuleManager()->get($service['position']);
            $serviceNameSpace = get_class($module);
            $serviceNameSpace = substr($serviceNameSpace, 0, strrpos($serviceNameSpace, '\\'));
            $serviceNameSpace = $serviceNameSpace.'\\Service\\'.$service['id'];
            $serviceFileContent[] = sprintf('namespace %s;', $serviceNameSpace);
        }
        
        if ( !empty($service['extends']) ) {
            $serviceParentClassName = $service['extends'];
        } else {
            $serviceParentClassName = 'X\\Core\\Service\\XService';
        }
        $serviceFileContent[] = sprintf('use %s;', $serviceParentClassName);
        
        $serviceFileContent[] = '';
        $serviceFileContent[] = '/**';
        $serviceFileContent[] = ' * ';
        $serviceFileContent[] = ' */';
        $serviceParentClassShortName = substr($serviceParentClassName, 1+strrpos($serviceParentClassName, '\\'));
        $serviceFileContent[] = sprintf('class Service extends %s {', $serviceParentClassShortName);
        
        $serviceFileContent[] = $tab.'/**';
        $serviceFileContent[] = $tab.' * The name of service. ';
        $serviceFileContent[] = $tab.' * @var string';
        $serviceFileContent[] = $tab.' */';
        $serviceFileContent[] = $tab.sprintf("protected static \$serviceName = '%s';", $service['id']);
        $serviceFileContent[] = '';
        
        $service['name'] = trim($service['name']);
        if ( !empty($service['name']) ) {
            $serviceFileContent[] = $tab.'/**';
            $serviceFileContent[] = $tab.' * (non-PHPdoc)';
            $serviceFileContent[] = $tab.' * @see \X\Core\Service\XService::getPrettyName()';
            $serviceFileContent[] = $tab.' */';
            $serviceFileContent[] = $tab.'public function getPrettyName() {';
            $serviceFileContent[] = $tab.$tab.sprintf('return %s;', var_export($service['name'], true));
            $serviceFileContent[] = $tab.'}';
            $serviceFileContent[] = '';
        }
        
        $service['description'] = trim($service['description']);
        if ( !empty($service['description']) ) {
            $serviceFileContent[] = $tab.'/**';
            $serviceFileContent[] = $tab.' * (non-PHPdoc)';
            $serviceFileContent[] = $tab.' * @see \X\Core\Service\XService::getDescription()';
            $serviceFileContent[] = $tab.' */';
            $serviceFileContent[] = $tab.'public function getDescription() {';
            $serviceFileContent[] = $tab.$tab.sprintf('return %s;', var_export($service['description'], true));
            $serviceFileContent[] = $tab.'}';
            $serviceFileContent[] = '';
        }
        
        $service['otherMethods'] = trim($service['otherMethods']);
        if ( !empty($service['otherMethods']) ) {
            $otherMethods = str_replace("\r", '', $service['otherMethods']);
            $otherMethods = explode("\n", $otherMethods);
            foreach ( $otherMethods as $index => $otherMethod ) {
                $otherMethods[$index] = $tab.$otherMethod;
            }
            $otherMethods = implode("\n", $otherMethods);
            $serviceFileContent[] = $otherMethods;
            $serviceFileContent[] = '';
        }
        
        $serviceFileContent[] = $tab.'/**';
        $serviceFileContent[] = $tab.' * (non-PHPdoc)';
        $serviceFileContent[] = $tab.' * @see \X\Core\Service\XService::getVersion()';
        $serviceFileContent[] = $tab.' */';
        $serviceFileContent[] = $tab.'public function getVersion() {';
        $serviceFileContent[] = $tab.$tab.'return array(0, 0, 1);';
        $serviceFileContent[] = $tab.'}';
        
        $serviceFileContent[] = '}';
        return implode("\n", $serviceFileContent);
    }
}