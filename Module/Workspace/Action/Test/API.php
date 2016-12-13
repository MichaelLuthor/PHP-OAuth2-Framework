<?php
namespace X\Module\Workspace\Action\Test;
use X\Module\Workspace\Util\PageAction;
use X\Module\Workspace\Util\API as APIUtil;
class API extends PageAction {
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction() {
        $apis = APIUtil::getAll();
        $test = $this->addParticle('Test/API');
        $test->getDataManager()->set('apis', $apis);
    }
    
    /**
     * {@inheritDoc}
     * @see \X\Module\Workspace\Util\PageAction::getPageOption()
     */
    protected function getPageOption() {
        return array(
            'title' => 'Test Authorization',
            'activeMenu' => array('main'=>'test', 'sub'=>'api'),
        );
    }
}