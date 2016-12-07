<?php
namespace X\Module\SystemManager\Action;
use X\Module\SystemManager\Util\Action;

class Index extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $this->render('Index');
    }
} 