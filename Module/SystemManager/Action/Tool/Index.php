<?php
namespace X\Module\SystemManager\Action\Tool;
use X\Core\X;
use X\Module\SystemManager\Util\Action;

class Index extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $this->render('Tool/Index');
    }
}