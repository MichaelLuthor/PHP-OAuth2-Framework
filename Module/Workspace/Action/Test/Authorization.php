<?php
namespace X\Module\Workspace\Action\Test;
use X\Module\Workspace\Util\PageAction;
class Authorization extends PageAction {
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction() {
        $config = $this->getModule()->getConfiguration();
        
        $auth = $this->addParticle('Test/Authorization');
        $auth->getDataManager()->set('clientID', $config->get('test_client_id'));
    }
    
    /**
     * {@inheritDoc}
     * @see \X\Module\Workspace\Util\PageAction::getPageOption()
     */
    protected function getPageOption() {
        return array(
            'title' => 'Test Authorization',
            'activeMenu' => array('main'=>'test', 'sub'=>'authorization'),
        );
    }
}