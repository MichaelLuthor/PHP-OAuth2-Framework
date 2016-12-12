<?php
namespace X\Module\Workspace\Action\API;
use X\Module\Workspace\Util\PageAction;
use X\Module\API\Module as APIModule;
class Index extends PageAction {
    /** @var string */
    protected $layout = self::LAYOUT_DEFAULT;
    
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction() {
        $apiModule = $this->getModule(APIModule::getModuleName());
        $actionPath = $apiModule->getPath('Action');
        $actions = $this->fetchActionPath($actionPath, $actionPath);
        
        $index = $this->addParticle('API/Index');
        $index->getDataManager()->setValues(array(
            'apis' => $actions,
        ));
    }
    
    /**
     * @param string $path
     * @return array
     */
    private function fetchActionPath($basePath, $path) {
        $actions = array();
        if ( is_file($path) ) {
            $actions[] = $this->readActionFile($path, $basePath);
        } else {
            $files = scandir($path);
            foreach ( $files as $file ) {
                if ( '.' === $file[0] ) { continue; }
                $newPath = $path.DIRECTORY_SEPARATOR.$file;
                $subAction = $this->fetchActionPath($basePath, $newPath);
                $actions = array_merge($actions,$subAction);
            }
        }
        return $actions;
    }
    
    /**
     * @param string $path
     * @param string $basePath
     * @return array
     */
    private function readActionFile( $path, $basePath ) {
        $apiInfo = array();
        
        $className = str_replace(array($basePath, '.php'), '', $path);
        if ( DIRECTORY_SEPARATOR === $className[0] ) {
            $className = substr($className, 1);
        }
        $apiInfo['name'] = $className;
        $className = "\\X\\Module\\API\\Action\\{$className}";
        
        $classInfo = new \ReflectionClass($className);
        $classComment = $this->parseDocComment($classInfo->getDocComment());
        $apiInfo['description'] = $classComment['description'];
        return $apiInfo;
    }
    
    /**
     * @param string $content
     * @return array
     */
    private function parseDocComment( $content ) {
        $content = preg_replace('#(\n\\s*\\**)#', "\n", $content);
        $content = trim(substr($content, 3, -1));
        
        $comment = array();
        $item = array(
            'key' => 'description',
            'value' => '',
        );
        $mark = 'value';
        for ( $i=0; $i<strlen($content); $i++ ) {
            if ( '@' === $content[$i] ) {
                $mark = ('value'===$mark) ? 'key' : 'value';
                $comment[] = $item;
                $item = array('key'=>'','value'=>'');
                continue;
            }
            if ( preg_match('#\s#', $content[$i]) && 'key'===$mark) {
                $mark = 'value';
                continue;
            }
            $item[$mark] .= $content[$i];
        }
        $comment[] = $item;
        
        $commentKV = array();
        foreach ( $comment as $item ) {
            $commentKV[trim($item['key'])] = trim($item['value']);
        }
        return $commentKV;
    }
    
    /**
     * {@inheritDoc}
     * @see \X\Module\Workspace\Util\PageAction::getPageOption()
     */
    protected function getPageOption() {
        return array(
            'title' => 'Management',
            'activeMenu' => array('main'=>'api', 'sub'=>'management'),
        );
    }
}