<?php
namespace X\Module\API\Action;
use X\Core\X;
use X\Service\XAction\Core\Handler\WebAction;
use X\Service\OAuth2\Service as OAuth2Service;
/**
 * 获取当前API版本号
 * @author michaelluthor
 * michaelluthor
 * @since V0.0.0
 * @example 
 *  This is a test version,
 *  DOn Ut Use is.
 * @link http://www.baidu.com
 */
class Version extends WebAction {
    /**
     * @param string $name 1sdfg sdfa sd
     * @param string $value asdf asdf asdf 
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $name, $value ) {
        /** @var $oauthService OAuth2Service */
        $oauthService = X::system()->getServiceManager()->get(OAuth2Service::getServiceName());
        if (!$oauthService->verifyResourceRequest(\OAuth2\Request::createFromGlobals())) {
            $oauthService->getResponse()->send();
            die;
        }
        
        echo json_encode(array(
            'status' => 'success',
            'message' => '',
            'data' => array(
                'version' => '0.0.1',
                'name' => $name,
                'value' => $value,
            ),
        ));
    }
}