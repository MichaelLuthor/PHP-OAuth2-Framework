<?php
namespace X\Service\XAction\Management\Action;
use X\Core\X;
use X\Module\SystemManager\Library\ServiceManagementAction;
use X\Module\SystemManager\Util\SystemManagerHelper;

class ActionOverview extends ServiceManagementAction {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $viewData = array();
        $actions = array();
        
        $modules = X::system()->getModuleManager()->getList();
        foreach ( $modules as $index => $module ) {
            $module = X::system()->getModuleManager()->get($module);
            if ( $this->getModule()->getName() === $module->getName() ) {
                continue;
            }
            $actions[] = $this->generateModuleListItem($module);
        }
        
        $viewData = array();
        $viewData['actions'] = $actions;
        $this->render('ActionOverView', $viewData);
    }
    
    /**
     * @param \X\Core\Module\XModule $module
     */
    private function generateModuleListItem( $module ) {
        $text = $module->getPrettyName();
        $icon = 'glyphicon glyphicon-briefcase';
        $attrs = array();
        $children = $this->generateListItemByPath($module, $module->getPath('Action'));
        $children[] = $this->generateAddNewActionNode($module->getName());
        return $this->generateListItem($text, $icon, $attrs, $children);
    }
    
    /**
     * @param \X\Core\Module\XModule $module
     * @param unknown $path
     */
    private function generateListItemByPath( $module, $path ) {
        $list = array();
        
        if ( !is_dir($path) ) {
            return $list;
        }
        
        
        $actionFiles = array();
        $actionFolders = array();
        $files = scandir($path);
        foreach ( $files as $file ) {
            if ( '.' === $file[0] ) {
                continue;
            }
            
            $filePath = $path.DIRECTORY_SEPARATOR.$file;
            if ( is_dir($filePath) ) {
                $children = $this->generateListItemByPath($module, $filePath);
                $prefix = str_replace($module->getPath('Action/'), '', $filePath);
                $prefix = array_map('lcfirst', explode(DIRECTORY_SEPARATOR, $prefix));
                $prefix = implode('/', $prefix).'/';
                $children[] = $this->generateAddNewActionNode($module->getName(), $prefix);
                $actionFolders[] = $this->generateListItem($file, 'glyphicon glyphicon-folder-open', array(), $children);
            } else {
                $attr = array();
                $attr['data-module'] = $module->getName();
                $attr['data-action'] = str_replace($module->getPath('Action/'), '', $filePath);
                $attr['data-action'] = str_replace('.php', '', $attr['data-action']);
                $attr['data-path'] = $filePath;
                $attr['data-type'] = 'action';
                
                $actionClass = SystemManagerHelper::getClassNamespaceName($module).'\\Action\\'.$attr['data-action'];
                if ( !is_subclass_of($actionClass, 'X\\Service\\XAction\\Core\\Util\\Action') ) {
                    continue;
                }
                
                $actionFiles[] = $this->generateListItem(basename($file, '.php'), 'glyphicon glyphicon-knight', $attr);
            }
        }
        
        $list = array_merge($actionFiles, $actionFolders);
        return $list;
    }
    
    /**
     * @param unknown $moduleName
     * @param string $actionPrefix
     * @return Ambigous <multitype:, multitype:unknown >
     */
    private function generateAddNewActionNode($moduleName, $actionPrefix='') {
        $attr = array();
        $attr['data-module'] = $moduleName;
        $attr['data-action-prefix'] = $actionPrefix;
        $attr['data-type'] = 'action-create';
        return $this->generateListItem('Add New Action', 'glyphicon glyphicon-flash', $attr);
    }
    
    /**
     * @return array
     */
    private function generateListItem( $text, $icon, $attrs=array(), $children=array() ) {
        $item = array();
        $item['text'] = $text;
        $item['icon'] = $icon;
        $item['children'] = $children;
        $item['a_attr'] = $attrs;
        return $item;
    }
}