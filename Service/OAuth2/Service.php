<?php
namespace X\Service\OAuth2;
use X\Core\Service\XService;
use OAuth2\RequestInterface;
use OAuth2\ResponseInterface;

class Service extends XService {
    /**
     * The name of service. 
     * @var string
     */
    protected static $serviceName = 'OAuth2';
    
    /** @var \OAuth2\Server */
    private $server = null;
    
    /**
     * {@inheritDoc}
     * @see \X\Core\Service\XService::start()
     */
    public function start() {
        parent::start();
        
        require_once(dirname(__FILE__).'/Library/OAuth2/Autoloader.php');
        \OAuth2\Autoloader::register();
        
        $config = $this->getConfiguration();
        $storageHandler = sprintf('\\OAuth2\\Storage\\%s', $config->get('storage_handler'));
        $storage = new $storageHandler($config->get('storage_params'));
        
        $server = new \OAuth2\Server($storage);
        if ( $config->get('enable_grant_type_client_credentials', true) ) {
            $server->addGrantType(new \OAuth2\GrantType\ClientCredentials($storage));
        }
        if ( $config->get('enable_grant_type_authorization_code', true) ) {
            $server->addGrantType(new \OAuth2\GrantType\AuthorizationCode($storage));
        }
        $this->server = $server;
    }
    
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @see \OAuth2\Server::validateAuthorizeRequest
     */
    public function validateAuthorizeRequest(RequestInterface $request, ResponseInterface $response = null) {
        return $this->server->validateAuthorizeRequest($request, $response);
    }
    
    /**
     * @param string $name
     * @param array $params
     * @return mixed
     */
    public function __call( $name, $params=array() ) {
        return call_user_func_array(array($this->server, $name), $params);
    }
    
    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::getPrettyName()
     */
    public function getPrettyName() {
        return 'OAuth2';
    }

    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::getDescription()
     */
    public function getDescription() {
        return 'OAuth2服务';
    }

    /**
     * (non-PHPdoc)
     * @see \X\Core\Service\XService::getVersion()
     */
    public function getVersion() {
        return array(0, 0, 1);
    }
}