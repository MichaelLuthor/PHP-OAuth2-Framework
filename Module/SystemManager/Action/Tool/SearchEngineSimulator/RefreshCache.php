<?php
namespace X\Module\SystemManager\Action\Tool\SearchEngineSimulator;
use X\Module\SystemManager\Util\Action;
class RefreshCache extends Action {
    /**
     * {@inheritDoc}
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $link = $this->getParam('link');
        
        $resultPath = $this->getModule()->getPath('Data/SearchEngineSimulatorResultCache.json');
        $result = json_decode(file_get_contents($resultPath), true);
        $link = trim($link);
        $content = file_get_contents($link);
        $cache = array();
        $cache['link'] = $link;
        if ( preg_match('/<.*?title.*?>(.*?)<.*?\/.*?title.*?>/is', $content, $matches) ) {
            $cache['title'] = trim($matches[1]);
        } else {
            $cache['title'] = $link;
        }
        
        if ( preg_match('/<.*?meta.*?name.*?=.*?description.*?content.*?="(.*?)".*?>/is', $content, $matches) ) {
            $cache['description'] = trim($matches[1]);
        } else {
            $cache['description'] = '';
        }
        $result[$link] = $cache;
        file_put_contents($resultPath, json_encode($result, JSON_FORCE_OBJECT));
        $this->goBack();
    }
}