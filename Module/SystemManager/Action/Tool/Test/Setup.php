<?php
namespace X\Module\SystemManager\Action\Tool\Test;
use X\Core\X;
use X\Module\SystemManager\Util\Action;
use X\Module\SystemManager\Util\HttpError404Handler;

class Setup extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
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
        
        $setupPath = $target->getPath('Test/Setup');
        if ( !is_dir($setupPath) ) {
            return ;
        }
        
        $history = array();
        $historyPath = $target->getPath('Test/Setup/.history.php');
        if ( is_file($historyPath) ) {
            $history = require $historyPath;
        }
        
        $baseNamespace = substr(get_class($target), 0, strrpos(get_class($target), '\\'));
        $setupFiles = scandir($setupPath, SCANDIR_SORT_ASCENDING);
        foreach ( $setupFiles as $setupFile ) {
            if ( '.' === $setupFile[0] ) {
                continue;
            }
            
            $setup = $baseNamespace.'\\Test\\Setup\\'.basename($setupFile, '.php');
            if ( in_array($setup, $history) ) {
                continue;
            }
            
            $setup = new $setup();
            $setup->run();
            $history[] = get_class($setup);
        }
        
        $history = "<?php\nreturn ".var_export($history, true).";";
        file_put_contents($historyPath, $history);
        echo "Successed!";
    }
}