<?php
namespace X\Module\OAuth2\Action;
use X\Core\X;
use X\Service\XAction\Core\Handler\WebAction;
use X\Service\OAuth2\Service as OAuth2Service;
class Token extends WebAction {
    /**
     * @param string $grant_type Required in POST.
     * @param string $code Required in POST.
     * @param string $client_id Required in POST.
     * @param string $client_secret Required in POST.
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction() {
        /** @var $oauth2Service OAuth2Service */
        $oauth2Service = X::system()->getServiceManager()->get(OAuth2Service::getServiceName());
        $request = \OAuth2\Request::createFromGlobals();
        $oauth2Service->handleTokenRequest($request)->send();
    }
}