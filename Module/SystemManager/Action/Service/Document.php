<?php
namespace X\Module\SystemManager\Action\Service;
use X\Core\X;
use X\Module\SystemManager\Util\Action;
use X\Module\SystemManager\Util\HttpError404Handler;
use X\Module\SystemManager\Library\MichelfMarkDownParser\MichelfMarkDownParser;

class Document extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $service = X::system()->getServiceManager()->get($this->getParam('name'));
        if ( null === $service ) {
            HttpError404Handler::run();
        }
        
        $type = $this->getParam('type');
        $docPath = $service->getPath('Document/'.ucfirst($type).'.md');
        $docContent = null;
        if ( file_exists($docPath) ) {
            $docContent = file_get_contents($docPath);
            $docContent = MichelfMarkDownParser::defaultTransform($docContent);
        }
        
        $this->render('Service/Document', array('docContent'=>$docContent, 'service'=>$service));
    }
}