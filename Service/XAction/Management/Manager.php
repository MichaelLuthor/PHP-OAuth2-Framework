<?php
namespace X\Service\XAction\Management;
use X\Module\SystemManager\Library\ServiceManager;

class Manager extends ServiceManager {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Library\ServiceManager::getExtendedManagementTools()
     */
    public function getExtendedManagementTools() {
        $tools = array();
        $tools['initModule'] = array('name'=>'初始化模块');
        $tools['createAction'] = array('name'=>'创建行为处理器');
        $tools['actionsDefImport'] = array('name'=>'导入行为列表');
        $tools['actionOverview'] = array('name'=>'行为总览');
        return $tools;
    }
}