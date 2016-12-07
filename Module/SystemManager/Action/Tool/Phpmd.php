<?php
namespace X\Module\SystemManager\Action\Tool;
use X\Core\X;
use X\Module\SystemManager\Util\Action;
use X\Module\SystemManager\Util\HttpError404Handler;
use X\Module\SystemManager\Util\HttpError500Handler;

class Phpmd extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $phpmdPath = $this->getModule()->getPath('Tool/PHPMD/phpmd.phar');
        
        $target = null;
        switch ( $this->getParam('type', null) ) {
        case 'module' : $target = $this->getModuleByName($this->getParam('target')); break;
        case 'service' : $target = $this->getServiceByName($this->getParam('target')); break;
        default: break;
        }
        
        if ( null === $target ) {
            return HttpError404Handler::run();
        }
        
        $configuration = $target->getConfiguration('PHPMD');
        if ( empty($configuration) ) {
            return HttpError500Handler::run('PHPMD 配置文件无效！');
        }
        $tmpRuleSetPath = $this->generateTempRuleSetFile($target);
        
        $exclude = array_merge(array('Test/'), $configuration->get('exclude', array()));
        foreach ( $exclude as $index => $excludeItem ) {
            $exclude[$index] = $target->getPath($excludeItem);
        }
        
        $exclude = implode(',', $exclude);
        $targetPath = $target->getPath();
        $command = "php $phpmdPath $targetPath html $tmpRuleSetPath --exclude {$exclude}";
        set_time_limit(0);
        exec($command, $resultLines, $resultCode);
        foreach ( $resultLines as $line ) {
            echo $line, "\n";
        }
    }
    
    /**
     * @return string
     */
    private function generateTempRuleSetFile($target) {
        $tmpRuleSetPath = tempnam(sys_get_temp_dir(), 'RULESET');
        $configuration = $target->getConfiguration('PHPMD')->toArray();
        $buildInRuleNames = array('cleancode','codesize','controversial','design','naming','unusedcode');
        
        $phpmdConfig = array();
        foreach ( $configuration['rule'] as $rulesetName => $ruleSetConfig ) {
            $ruleSetPath = $rulesetName;
            if ( in_array($rulesetName, $buildInRuleNames) ) {
                $ruleSetPath = "rulesets/{$rulesetName}.xml";
            } else {
                $ruleSetPath = X::system()->getPath($rulesetName);
            }
            
            if ( !isset($ruleSetConfig['disabled']) ) {
                $ruleSetConfig['disabled'] = array();
            }
            
            $phpmdConfig[$ruleSetPath] = $ruleSetConfig;
        }
        
        $content = $this->renderView('Tool/PHPMDRuleSetXML', array('config'=>$phpmdConfig));
        file_put_contents($tmpRuleSetPath, $content);
        
        chmod($tmpRuleSetPath, 0777);
        return $tmpRuleSetPath;
    }
}