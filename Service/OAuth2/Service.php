<?php
namespace X\Service\OAuth2;
use X\Core\Service\XService;

/**
 * 
 */
class Service extends XService {
    /**
     * The name of service. 
     * @var string
     */
    protected static $serviceName = 'OAuth2';

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