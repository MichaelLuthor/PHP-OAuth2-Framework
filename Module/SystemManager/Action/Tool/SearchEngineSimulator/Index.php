<?php
namespace X\Module\SystemManager\Action\Tool\SearchEngineSimulator;
use X\Module\SystemManager\Util\Action;
class Index extends Action {
    /**
     * {@inheritDoc}
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $result = $this->getModule()->getPath('Data/SearchEngineSimulatorResultCache.json');
        if ( file_exists($result) ) {
            $result = json_decode(file_get_contents($result), true);
        } else {
            $links = $this->getModule()->getPath('Data/SearchEngineSimulatorLinks.txt');
            $links = explode("\n", file_get_contents($links));
            $cache = array();
            foreach ( $links as $link ) {
                $link = trim($link);
                $content = file_get_contents($link);
                $cache[$link] = array();
                $cache[$link]['link'] = $link;
                if ( preg_match('/<.*?title.*?>(.*?)<.*?\/.*?title.*?>/is', $content, $matches) ) {
                    $cache[$link]['title'] = trim($matches[1]);
                } else {
                    $cache[$link]['title'] = $link;
                }
                
                if ( preg_match('/<.*?meta.*?name.*?=.*?description.*?content.*?="(.*?)".*?>/is', $content, $matches) ) {
                    $cache[$link]['description'] = trim($matches[1]);
                } else {
                    $cache[$link]['description'] = '';
                }
            }
            file_put_contents($result, json_encode($cache, JSON_FORCE_OBJECT));
            $result = $cache;
        }
        
        $count = count($result);
        $config = $this->getModule()->getConfiguration()->get('SearchEngineSimulator', array());
        $pagesize = $config['pagesize'];
        $page = (int)$this->getParam('page', 1);
        $result = array_slice($result, ($page-1)*$pagesize, $pagesize);
        $titleLength = $config['titleLength'];
        $descriptionLength = $config['descriptionLength'];
        foreach ( $result as $index => $resultItem ) {
            if ( mb_strlen($resultItem['title'],'UTF8') > $titleLength ) {
                $result[$index]['title'] = mb_substr($resultItem['title'], 0, $titleLength, 'UTF8').'...';
            }
            if ( mb_strlen($resultItem['description'],'UTF8') > $descriptionLength ) {
                $result[$index]['description'] = mb_substr($resultItem['description'], 0, $descriptionLength, 'UTF8').'...';
            }
        }
        
        $this->render('Tool/SearchEngineResult', array(
            'result'    => $result,
            'count'     => $count,
            'current'   => $page,
            'size'      => 10,
            'keyword'   => $config['keyword'],
        ));
    }
}