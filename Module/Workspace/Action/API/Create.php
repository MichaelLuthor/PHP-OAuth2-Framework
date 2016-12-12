<?php
namespace X\Module\Workspace\Action\API;
use X\Module\Workspace\Util\PageAction;
use X\Service\XView\Core\Util\HtmlView\ParticleView;
use X\Module\Workspace\Util\User;
use X\Module\API\Module;
class Create extends PageAction {
    /** @var string */
    protected $layout = self::LAYOUT_DEFAULT;
    
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction() {
        $error = null;
        $form = array();
        if ( isset($_POST['form']) ) {
            $form = $_POST['form'];
            try {
                $this->generateAPIAction($_POST['form']);
                $this->gotoURL('/index.php?module=Workspace&action=API/Index');
            } catch ( \Exception $e ) {
                $error = $e->getMessage();
            }
        } else {
            $form = array(
                'param' => array(),
                'name' => '',
                'description' => '',
            );
        }
        
        $create = $this->addParticle('API/Create');
        $create->getDataManager()->set('error', $error);
        $create->getDataManager()->set('form', $form);
    }
    
    /**
     * @param unknown $form
     */
    private function generateAPIAction($form) {
        $this->validateFormData($form);
        $form['name'] = explode('\\', $form['name']);
        $form['name'] = array_map('ucfirst', $form['name']);
        $form['name'] = implode('\\', $form['name']);
        
        $form['namespace'] = '';
        if ( false !== strpos($form['name'], '\\') ) {
            $form['namespace'] = substr($form['name'], 0, strrpos($form['name'], '\\'));;
            $form['name'] = str_replace($form['namespace'], '', $form['name']);
            $form['name'] = substr($form['name'], 1);
        }
        
        $currentAccount = User::getCurrentAccount();
        $form['author'] = $currentAccount['account'];
        
        foreach ( $form['param'] as &$param ) {
            $defaultValue = '';
            if ( !empty($param['default']) ) {
                $defaultValue = $param['default'];
                if ( !is_numeric($defaultValue) ) {
                    $firstChar = $defaultValue[0];
                    $lastChar = $defaultValue[strlen($defaultValue)-1];
                    if ( '"'!==$firstChar && '"'!==$lastChar && "'"!==$firstChar && "'"!==$lastChar ) {
                        $defaultValue = "'{$defaultValue}'";
                    }
                }
                $param['default'] = $defaultValue;
            }
        }
        
        $templatePath = $this->getModule()->getPath('View/Particle/API/APIAction.php');
        $template = new ParticleView('api-action', $templatePath);
        $template->getDataManager()->setValues($form);
        $actionContent = "<?php\n".$template->toString();
        
        $actionPath = $this->getModule(Module::getModuleName())->getPath('Action/');
        $actionPath = $actionPath.str_replace('\\', DIRECTORY_SEPARATOR, $form['namespace']);
        if ( !is_dir($actionPath) ) {
            mkdir($actionPath, 0777, true);
        }
        $actionPath = $actionPath.DIRECTORY_SEPARATOR.$form['name'].'.php';
        file_put_contents($actionPath, $actionContent);
    }
    
    /** @return void */
    private function validateFormData( $form ) {
        if ( empty($form['name']) ) {
            throw new \Exception('API name could not be empty.');
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \X\Module\Workspace\Util\PageAction::getPageOption()
     */
    protected function getPageOption() {
        return array(
            'title' => 'Create New API',
            'activeMenu' => array('main'=>'api', 'sub'=>'new'),
        );
    }
}