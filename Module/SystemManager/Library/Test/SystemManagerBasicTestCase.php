<?php
namespace X\Module\SystemManager\Library\Test;
use X\Module\SystemManager\Library\Test\TestTrait\SystemManagerTestTrait;
/**
 * 
 */
abstract class SystemManagerBasicTestCase extends \PHPUnit_Framework_TestCase {
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
        $this->stopXFramework();
    }
}