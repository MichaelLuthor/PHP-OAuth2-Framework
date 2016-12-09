<?php
namespace X\Module\Workspace\Action;
use X\Module\Workspace\Util\PageAction;
use X\Module\Workspace\Util\User;
class Login extends PageAction {
    /** @var string */
    protected $layout = self::LAYOUT_SINGLE_COLUMN_FULL_WIDTH;
    /** @var boolean */
    protected $isLoginRequired = false;
    
    /**
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction() {
        if ( !User::isGuestAccount() ) {
            $this->gotoURL('index.php?module=Workspace&action=index');
        }
        
        $loginError = null;
        if ( isset($_POST['form']) ) {
            try {
                User::login($_POST['form']['account'], $_POST['form']['password']);
                $this->gotoURL('index.php?module=Workspace&action=index');
            } catch ( \Exception $e ) {
                $loginError = $e->getMessage();
            }
        }
        
        $login = $this->addParticle('Login');
        $login->getDataManager()->set('loginError', $loginError);
    }
}