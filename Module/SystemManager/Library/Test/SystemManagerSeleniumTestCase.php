<?php
namespace X\Module\SystemManager\Library\Test;
use X\Module\SystemManager\Library\Test\TestTrait\SystemManagerTestTrait;
/**
 *
 */
abstract class SystemManagerSeleniumTestCase extends \PHPUnit_Extensions_Selenium2TestCase {
    /**
     * 
     */
    use SystemManagerTestTrait;
    
    /**
     * {@inheritDoc}
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    protected function setUp() {
        $this->startXFramework();
    }
    
    /**
     * {@inheritDoc}
     * @see PHPUnit_Framework_TestCase::tearDown()
     */
    protected function tearDown() {
        //$this->stopXFramework();
    }
    
    /**
     * @param unknown $url
     * @return array
     */
    public function getParametersFormURL( $url ) {
        $params = parse_url($url, PHP_URL_QUERY);
        if ( null === $params ) {
            return array();
        }
        parse_str($params, $params);
        return $params;
    }
}