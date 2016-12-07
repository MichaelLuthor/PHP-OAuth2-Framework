<?php
namespace X\Module\SystemManager\Action\Tool\SearchEngineSimulator;
use X\Module\SystemManager\Util\Action;
class DeleteCache extends Action {
    /**
     * {@inheritDoc}
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $link = $this->getParam('link');
        
        $resultPath = $this->getModule()->getPath('Data/SearchEngineSimulatorResultCache.json');
        $result = json_decode(file_get_contents($resultPath), true);
        $link = trim($link);
        unset($result[$link]);
        file_put_contents($resultPath, json_encode($result, JSON_FORCE_OBJECT));
        $this->goBack();
    }
}