<?php
namespace X\Module\SystemManager\Action\Tool;
use X\Core\X;
use X\Module\SystemManager\Util\Action;
use X\Module\SystemManager\Util\HttpError404Handler;

class Phpcpd extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $phpmdPath = $this->getModule()->getPath('Tool/PHPCPD/phpcpd.phar');
        
        
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
        
        $resultFilePath = tempnam(sys_get_temp_dir(), 'PHPCPD');
        $targetPath = $target->getPath();
        $command = "php $phpmdPath $targetPath --log-pmd=$resultFilePath";
        
        set_time_limit(0);
        exec($command, $resultLines, $resultCode);
        
        $result = array();
        $xml = simplexml_load_file($resultFilePath);
        foreach ( $xml->duplication as $duplication ) {
            $resultItem = array( 'files'=>array(), 'code'=>(string)$duplication->codefragment );
            foreach ( $duplication->file as $file ) {
                $attr = $file->attributes();
                $resultItem['files'][] = array('path'=>(string)$attr['path'], 'line'=>(string)$attr['line']);
            }
            $result[] = $resultItem;
        }
        
        $this->render('Tool/PhpcpdResult', array(
            'result'=>$result,
            'target' => $target,
            'targetDetailPage' => $this->createURL("{$type}/detail", array('name'=>$target->getName())),
        ));
        
        unlink($resultFilePath);
    }
}