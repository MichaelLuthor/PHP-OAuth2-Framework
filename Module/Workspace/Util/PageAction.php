<?php
namespace X\Module\Workspace\Util;
use X\Core\X;
use X\Service\XAction\Core\Handler\WebAction;
use X\Service\XView\Service as XViewService;
/**
 *
 */
abstract class PageAction extends WebAction {
    /** @var string */
    const LAYOUT_SINGLE_COLUMN_FULL_WIDTH = 'SingleColumnFullWidth';
    /** @var string */
    const LAYOUT_DEFAULT = 'Default';
    
    /** @var \X\Service\XView\Core\Handler\Html */
    private $pageView = null;
    /** @var string */
    protected $layout = self::LAYOUT_DEFAULT;
    /** @var boolean */
    protected $isLoginRequired = true;
    
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::beforeRunAction()
     */
    protected function beforeRunAction() {
        parent::beforeRunAction();
        
        if ( $this->isLoginRequired && User::isGuestAccount() ) {
            $this->gotoURL('index.php?module=Workspace&action=login');
        }
        
        /** @var $viewService XViewService */
        $viewService = X::system()->getServiceManager()->get(XViewService::getServiceName());
        $this->pageView = $viewService->createHtml('Index');
        $this->pageView->getMetaManager()->setCharset('UTF-8');
    }
    
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::afterRunAction()
     */
    protected function afterRunAction() {
        $path = X::system()->getPath("Module/Workspace/View/Layout/{$this->layout}.php");
        $this->pageView->setLayout($path);
        $this->pageView->display();
        
        parent::afterRunAction();
    }
    
    /**
     * @param string $name
     * @return \X\Service\XView\Core\Util\HtmlView\ParticleView
     */
    public function addParticle( $name ) {
        $path = X::system()->getPath("Module/Workspace/View/Particle/{$name}.php");
        return $this->pageView->getParticleViewManager()->load($name, $path);
    }
    
    /**
     * @param string $name
     * @return \X\Core\Module\XModule
     */
    protected function getModule( $name=null ) {
        if ( null === $name ) {
            $name = 'Workspace';
        }
        return X::system()->getModuleManager()->get($name);
    }
}