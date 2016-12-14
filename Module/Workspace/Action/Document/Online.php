<?php
namespace X\Module\Workspace\Action\Document;
use X\Module\Workspace\Util\PageAction;
use X\Module\API\Module;
use X\Module\Workspace\Util\API;
class Online extends PageAction {
    /** @var string */
    protected $layout = self::LAYOUT_DEFAULT;
    
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction() {
        $apis = API::getAll();
        
        $doc = $this->addParticle('Document/Online');
        $doc->getDataManager()->set('apis', $apis);
    }
    
    /**
     * {@inheritDoc}
     * @see \X\Module\Workspace\Util\PageAction::getPageOption()
     */
    protected function getPageOption() {
        return array(
            'title' => 'Document Online',
            'activeMenu' => array('main'=>'document', 'sub'=>'online'),
        );
    }
}