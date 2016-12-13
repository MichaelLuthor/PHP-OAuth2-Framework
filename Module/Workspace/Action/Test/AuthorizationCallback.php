<?php
namespace X\Module\Workspace\Action\Test;
use X\Core\X;
use X\Service\XAction\Core\Handler\WebAction;
use X\Module\Workspace\Module as WorkspaceModule;
class AuthorizationCallback extends WebAction {
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction() {
        $workspace = X::system()->getModuleManager()->get(WorkspaceModule::getModuleName());
        $config = $workspace->getConfiguration();
        
        $ch = curl_init("http://{$_SERVER['SERVER_NAME']}/index.php?module=OAuth2&action=token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,array(
            'grant_type' => 'authorization_code',
            'code' => $_REQUEST['code'],
            'client_id' => $config->get('test_client_id'),
            'client_secret' => $config->get('test_client_secret'),
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        
        $response = json_decode($response, true);
        echo "<pre>";
        var_export($response);
        echo "</pre>";
    }
}