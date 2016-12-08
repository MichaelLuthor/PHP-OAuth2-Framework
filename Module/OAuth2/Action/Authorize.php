<?php
namespace X\Module\OAuth2\Action;
use X\Core\X;
use X\Service\XAction\Core\Handler\WebAction;
use X\Service\XView\Service as XViewService;
class Authorize extends WebAction {
    /** @var array */
    private $demoAccounts = array(
        'demo1' => array('password'=>'demo1'),
        'demo2' => array('password'=>'demo2'),
    );
    
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $form=array() ) {
        if ( isset($form['authorize']) ) {
            return $this->doAuthorize($form);
        }
        
        /** @var $viewService XViewService */
        $viewService = X::system()->getServiceManager()->get(XViewService::getServiceName());
        $html = $viewService->createHtml('Authorize');
        $html->setLayout(X::system()->getPath('Module/OAuth2/View/Layout/Signle.php'));
        $html->getParticleViewManager()->load('Authorize', X::system()->getPath('Module/OAuth2/View/Particle/Authorize.php'));
        $html->display();
    }
    
    /** @return void */
    private function doAuthorize( $form ) {
        var_dump($form);
    }
}