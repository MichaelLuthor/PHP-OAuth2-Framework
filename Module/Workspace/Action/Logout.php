<?php
namespace X\Module\Workspace\Action;
use X\Module\Workspace\Util\PageAction;
use X\Module\Workspace\Util\User;
class Logout extends PageAction {
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction() {
        User::logout();
        $this->gotoURL('index.php?module=Workspace&action=login');
    }
}