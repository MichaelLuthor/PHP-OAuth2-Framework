<?php
namespace X\Service\XAction\Management\Action;
use X\Module\SystemManager\Library\ServiceManagementAction;

class ActionFileView extends ServiceManagementAction {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $path = $this->getParam('path');
        $content = file_get_contents($path);
        echo json_encode(array('content'=>$content, 'path'=>$path));
    }
}