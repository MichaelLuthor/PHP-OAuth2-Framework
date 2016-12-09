<?php
namespace X\Module\Workspace\Util;
use X\Core\X;
use X\Module\Workspace\Module as WorkspaceModule;
class User {
    /** @var array */
    private static $accounts = array();
    
    /** @return void */
    private static function initAccounts() {
        $workspace = X::system()->getModuleManager()->get(WorkspaceModule::getModuleName());
        self::$accounts = $workspace->getConfiguration('User');
    }
    
    /**
     * @param string $account
     * @param string $password
     */
    public static function login($account, $password) {
        self::initAccounts();
        if ( !self::$accounts->has($account) ) {
            throw new \Exception('Wrong account or password.');
        }
        
        $accountInfo = self::$accounts->get($account);
        if ( $accountInfo['password'] !== self::encryptPassword($password) ) {
            throw new \Exception('Wrong account or password.');
        }
        
        $_SESSION['CurrentAccount'] = array_merge(array('account'=>$account), $accountInfo);
    }
    
    /** @return boolean */
    public static function isGuestAccount() {
        return !isset($_SESSION['CurrentAccount']);
    }
    
    /** @return void */
    public static function logout() {
        unset($_SESSION['CurrentAccount']);
        $_SESSION = array();
    }
    
    /** @return string */
    private static function encryptPassword($password) {
        return $password;
    }
}