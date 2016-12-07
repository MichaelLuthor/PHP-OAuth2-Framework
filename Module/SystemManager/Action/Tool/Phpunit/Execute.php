<?php
namespace X\Module\SystemManager\Action\Tool\Phpunit;
use X\Module\SystemManager\Util\Action;
use X\Module\SystemManager\Util\HttpError404Handler;
use X\Module\SystemManager\Util\HttpError500Handler;
use X\Module\SystemManager\Util\SystemManagerHelper;
class Execute extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $phpunitPath = $this->getModule()->getPath('Tool/PHPUnit/phpunit.phar');
        
        $type = $this->getParam('type', null);
        $target = null;
        switch ( $type ) {
            case 'module' : $target = $this->getModuleByName($this->getParam('target')); break;
            case 'service' : $target = $this->getServiceByName($this->getParam('target')); break;
            default: break;
        }
        
        if ( null === $target ) {
            return HttpError404Handler::run();
        }
        
        SystemManagerHelper::createFolder($target->getPath('Data/TestResult'));
        
        # output
        if (ob_get_level() == 0) ob_start();
        echo str_repeat(" ", 4096);
        echo "\n";
        flush();
        
        $commandFilePath = $target->getPath('Data/test.cmd');
        file_put_contents($commandFilePath, "cls\n");
        
        $startupFile = $target->getPath('Configuration/PHPUnit/Startup.php');
        if ( file_exists($startupFile) ) {
            file_put_contents($commandFilePath, "php {$startupFile}\n", FILE_APPEND);
        }
        echo system("php {$startupFile}");
        echo "<br/>\n";
        
        $testPath = $target->getPath('Test');
        $xmlConfigPath = $this->generatePHPUnitXMLConfiguration($target);
        $command = "php $phpunitPath --verbose --debug --configuration $xmlConfigPath $testPath";
        $commandFileContent = "php ^\n$phpunitPath ^\n--verbose ^\n--debug ^\n--configuration $xmlConfigPath ^\n$testPath\n";
        file_put_contents($commandFilePath, $commandFileContent, FILE_APPEND);
        
        $errorPath = tempnam(sys_get_temp_dir(), 'PUER');
        $descriptorspec = array(
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => array('file', $errorPath, 'a')
        );
        $workPath = $this->getModule()->getPath('Tool/PHPUnit');
        $phpunit = proc_open($command, $descriptorspec, $pipes, $workPath);
        
        if ( !is_resource($phpunit) ) {
            HttpError500Handler::run('无法执行PHPUnit.');
        }
        
        echo $command."<br><br>";
        while( !feof($pipes[1]) ){
            $char = fread($pipes[1], 1);
            switch ( $char ) {
            case "\n" : $char = "<br>\n"; break;
            case " "  : $char = '&nbsp;'; break;
            case "\t" : $char = '&nbsp;&nbsp;&nbsp;&nbsp;'; break;
            default : break;
            }
            echo $char;
            ob_flush();
            flush();
        }
        
        echo "<br/>\n";
        $endupFile = $target->getPath('Configuration/PHPUnit/Endup.php');
        if ( file_exists($endupFile) ) {
            file_put_contents($commandFilePath, "php {$endupFile}\n", FILE_APPEND);
        }
        echo system("php {$endupFile}");
        echo "<br/>\n";
        
        echo "Test Finished.";
        fclose($pipes[0]);
        fclose($pipes[1]);
        $return_value = proc_close($phpunit);
        
        unlink($errorPath);
    }
    
    /**
     * @return string
     */
    private function generatePHPUnitXMLConfiguration( $target ) {
        $xmlPath = $target->getPath('Data/phpunit-configuration.xml');
        $xmlContent = $this->renderView('Tool/PHPUnitConfigurationXML', array(
            'bootstrap' => $this->getModule()->getPath('Tool/PHPUnit/bootstrap.php'),
            'loggingPath' => $target->getPath('Data/TestResult'),
        ));
        file_put_contents($xmlPath, $xmlContent);
        return $xmlPath;
    }
}