<?php
namespace X\Module\Workspace\Action\Document;
use X\Core\X;
use X\Module\Workspace\Util\PageAction;
use X\Module\API\Module;
use X\Service\XView\Core\Util\HtmlView\ParticleView;
use X\Module\Workspace\Util\API;
class Export extends PageAction {
    /** @var string */
    protected $layout = self::LAYOUT_DEFAULT;
    
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( $type=null ) {
        if ( null !== $type ) {
            $type = strtoupper($type);
            $handler = "handle_{$type}";
            if ( method_exists($this, $handler) ) {
                call_user_func_array(array($this, $handler), array());
            }
            X::system()->stop();
        }
        $doc = $this->addParticle('Document/Export');
    }
    
    /** @return string */
    private function renderTemplate ( $type='HTML' ) {
        $templatePath = $this->getModule()->getPath("View/Particle/Document/Document{$type}.php");
        $template = new ParticleView('document', $templatePath);
        $template->getDataManager()->setValues(array(
            'apis' => API::getAll(),
        ));
        
        $content = $template->toString();
        return $content;
    }
    
    /** @return void */
    private function handle_HTML() {
        echo $this->renderTemplate('HTML');
    }
    
    /** @return void */
    private function handle_WORD() {
        require_once $this->getModule()->getPath('Library/PhpWord/Autoloader.php');
        
        $wordpath = tempnam(sys_get_temp_dir(), 'APIDOC');
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $this->renderTemplate('WORD'));
        $phpWord->save($wordpath, 'Word2007');
        
        header("Cache-Control: public");
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        header('Content-Disposition: attachment; filename=API.docx');
        header("Pragma:no-cache");
        header("Expires:0");
        readfile($wordpath);
        unlink($wordpath);
    }
    
    /** @return void */
    private function handle_PDF() {
        require_once $this->getModule()->getPath('Library/mpdf/Autoloader.php');
        
        $mpdf = new \mPDF();
        $mpdf->WriteHTML($this->renderTemplate('HTML'));
        $mpdf->Output();
    }
    
    /** @return void */
    private function handle_TEXT() {
        header("Cache-Control: public");
        Header("Content-type: text/plain");
        Header("Accept-Ranges: bytes");
        header('Content-Disposition: attachment; filename=API.txt');
        header("Pragma:no-cache");
        header("Expires:0");
        echo $this->renderTemplate('TEXT');
    }
    
    /**
     * {@inheritDoc}
     * @see \X\Module\Workspace\Util\PageAction::getPageOption()
     */
    protected function getPageOption() {
        return array(
            'title' => 'Document Export',
            'activeMenu' => array('main'=>'document', 'sub'=>'export'),
        );
    }
}