<?php
namespace X\Module\Workspace\Action;
use X\Module\Workspace\Util\User;
use X\Service\XAction\Core\Handler\WebAction;
class Logout extends WebAction {
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction() {
        User::logout();
        $this->gotoURL('index.php?module=Workspace&action=login');
    }
}