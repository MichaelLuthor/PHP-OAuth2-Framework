<?php
namespace X\Module\Workspace\Action;
use X\Core\X;
use X\Service\XAction\Core\Handler\WebAction;
use X\Service\XView\Service as XViewService;
class Index extends WebAction {
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction() {
        /** @var $viewService XViewService */
        $viewService = X::system()->getServiceManager()->get(XViewService::getServiceName());
        $html = $viewService->createHtml('Index');
        $html->setLayout(X::system()->getPath('Module/Workspace/View/Layout/Default.php'));
        $html->display();
    }
}