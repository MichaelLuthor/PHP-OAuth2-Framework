<?php
namespace X\Module\SystemManager\Action\Tool\SearchEngineSimulator;
use X\Module\SystemManager\Util\Action;
class CleanCache extends Action {
    /**
     * {@inheritDoc}
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $result = $this->getModule()->getPath('Data/SearchEngineSimulatorResultCache.json');
        if ( file_exists($result) ) {
            unlink($result);
            $this->gotoURL('tool/SearchEngineSimulator/Index');
        }
    }
}