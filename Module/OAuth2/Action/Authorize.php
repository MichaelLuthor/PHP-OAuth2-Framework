<?php
namespace X\Module\OAuth2\Action;
use X\Core\X;
use X\Service\XAction\Core\Handler\WebAction;
use X\Service\XView\Service as XViewService;
use X\Service\OAuth2\Service as OAuth2Service;
class Authorize extends WebAction {
    /** @var array */
    private $demoAccounts = array(
        'demo1' => array('password'=>'demo1'),
        'demo2' => array('password'=>'demo2'),
    );
    
    /**
     * @param string $client_id Required in GET
     * @param string $response_type Required in GET
     * @param string $state Required in GET
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $form=array() ) {
        /** @var $oauth2Service OAuth2Service */
        $oauth2Service = X::system()->getServiceManager()->get(OAuth2Service::getServiceName());
        $request = \OAuth2\Request::createFromGlobals();
        $response = new \OAuth2\Response();
        if (!$oauth2Service->validateAuthorizeRequest($request, $response)) {
            $response->send();
            die;
        }
        
        $errors = array();
        if ( isset($form['authorize']) ) {
            $errors = $this->doAuthorize($form, $request, $response);
        }
        
        /** @var $viewService XViewService */
        $viewService = X::system()->getServiceManager()->get(XViewService::getServiceName());
        $html = $viewService->createHtml('Authorize');
        $html->setLayout(X::system()->getPath('Module/OAuth2/View/Layout/Signle.php'));
        
        $authorize = $html->getParticleViewManager()->load('Authorize', X::system()->getPath('Module/OAuth2/View/Particle/Authorize.php'));
        $authorize->getDataManager()->set('errors', $errors);
        $html->display();
    }
    
    /** @return void */
    private function doAuthorize( $form, $request, \OAuth2\Response $response ) {
        /** @var $oauth2Service OAuth2Service */
        $oauth2Service = X::system()->getServiceManager()->get(OAuth2Service::getServiceName());
        
        $isAuthorized = $this->authorizeAccountAndPassword($form['account'], $form['password']);
        $oauth2Service->handleAuthorizeRequest($request, $response, $isAuthorized);
        if ($isAuthorized) {
            $response->send(); 
            die();
        }
        return array('用户名或密码不正确。');
    }
    
    /**
     * @param string $account
     * @param string $password
     */
    private function authorizeAccountAndPassword( $account, $password ) {
        return isset($this->demoAccounts[$account]) 
        ? (($this->demoAccounts[$account]['password']===$password)
            ? true 
            : false)
        : false;
    }
}