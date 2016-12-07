<?php
namespace X\Module\OAuth2\Action;
use X\Service\XAction\Core\Handler\WebAction;
class Authorize extends WebAction {
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction() {
        echo "OK";
    }
}