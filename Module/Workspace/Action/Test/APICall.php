<?php
namespace X\Module\Workspace\Action\Test;
use X\Core\X;
use X\Service\XAction\Core\Handler\WebAction;
use X\Module\Workspace\Module as WorkspaceModule;
class APICall extends WebAction {
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $name, $params ) {
        $workspace = X::system()->getModuleManager()->get(WorkspaceModule::getModuleName());
        $config = $workspace->getConfiguration();
        
        $ch = curl_init("http://{$_SERVER['SERVER_NAME']}/index.php?module=OAuth2&action=token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,array(
            'grant_type' => 'client_credentials',
            'client_id' => $config->get('test_client_id'),
            'client_secret' => $config->get('test_client_secret'),
        ));
        $response = json_decode(curl_exec($ch), true);
        $accessToken = $response['access_token'];
        curl_close($ch);
        $ch = null;
        
        $postData = array_merge(array(
            'access_token' => $accessToken,
        ), $params);
        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $ch = curl_init("http://{$_SERVER['SERVER_NAME']}/index.php?module=API&action={$name}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        $response = json_decode(curl_exec($ch),true);
        curl_close($ch);
        
        var_export($response);
    }
}