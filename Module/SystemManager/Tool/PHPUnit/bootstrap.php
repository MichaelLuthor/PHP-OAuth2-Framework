<?php
# load selenium extension.
require_once dirname(__FILE__).'/Extensions/SeleniumCommon/Autoload.php';
# implement x frameword autoload so that we can start X framework by setup/teardown in test case.
spl_autoload_register(function( $name ) {
    $basepath = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
    $path = explode('\\', $name);
    if ( 1 === count($path) ) {
        return;
    }
    $path[0] = $basepath;
    $path[count($path)-1] .= '.php';
    $path = implode(DIRECTORY_SEPARATOR, $path);
    if ( is_file($path) ) {
        require $path;
    }
});

$debugStartup = getenv('ZEND_STUDIO_PHPUNIT_DEBUG_STARTUP');
if ( false !== $debugStartup ) {
    $basepath = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
    $startup = "{$basepath}/Module/{$debugStartup}/Configuration/PHPUnit/Startup.php";
    if ( file_exists($startup) ) {
        require_once $startup;
    }
}