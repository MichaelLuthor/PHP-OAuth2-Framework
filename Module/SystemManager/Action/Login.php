<?php
namespace X\Module\SystemManager\Action;
use X\Module\SystemManager\Util\Action;

class Login extends Action {
    /**
     * (non-PHPdoc)
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        if ( isset($_POST['form']) 
        && $this->login($_POST['form']['account'], $_POST['form']['password'])) {
            return $this->gotoURL('index');
        }
        $this->render('Login');
    }
    
    /**
     * {@inheritDoc}
     * @see \X\Module\SystemManager\Util\Action::isLoginRequired()
     */
    protected function isLoginRequired() {
        return false;
    }
} 