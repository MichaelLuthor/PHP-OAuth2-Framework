<?php
namespace X\Module\Workspace\Util;
use X\Core\X;
use X\Service\XAction\Core\Handler\WebAction;
/**
 *
 */
abstract class ExecAction extends WebAction {
    /** @var boolean */
    protected $isLoginRequired = true;
    
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::beforeRunAction()
     */
    protected function beforeRunAction() {
        parent::beforeRunAction();
        
        if ( $this->isLoginRequired && User::isGuestAccount() ) {
            $this->gotoURL('index.php?module=Workspace&action=login');
        }
    }
    
    /** @param array $data */
    public function success( $data=array() ) {
        $this->resposne(true, '', $data);
    }
    
    /** @param string $message */
    public function error( $message ) {
        $this->resposne(false, $message, array());
    }
    
    /**
     * @param boolean $isSuccess
     * @param string $message
     * @param array $data
     */
    private function resposne( $isSuccess, $message, $data ) {
        echo json_encode(array(
            'status' => $isSuccess ? 'success' : 'failed',
            'message' => $message,
            'data' => $data,
        ));
        X::system()->stop();
    }
    
    /**
     * @param string $name
     * @return \X\Core\Module\XModule
     */
    protected function getModule( $name=null ) {
        if ( null === $name ) {
            $name = 'Workspace';
        }
        return X::system()->getModuleManager()->get($name);
    }
}