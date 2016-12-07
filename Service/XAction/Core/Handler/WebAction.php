<?php
/**
 * This file is part of x-action service.
 * @license LGPL http://www.gnu.de/documents/lgpl.en.html
 */
namespace X\Service\XAction\Core\Handler;
/**
 * 
 */
use X\Core\X;
use X\Module\Dionysos\Library\Helper\URLHandler;
/**
 * The Web action class.
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
abstract class WebAction extends \X\Service\XAction\Core\Util\Action {
    /**
     * build up a url string.
     * @param string $path
     * @param array $params
     * @return string
     */
    public function createURL( $path, $params=null ) {
        $urlInfo = parse_url($path);
        if ( null !== $params ) {
            $parmConnector = (isset($urlInfo['query'])) ? '&' : '?';
            $path = $path.$parmConnector.http_build_query($params);
        }
        return $path;
    }
    
    /**
     * Jump to target url and exit the script.
     * @param string $url The target url to jump to.
     * @param array  $parms The parameters to that url
     */
    public function gotoURL( $url, $parms=null, $format=true ) {
        if ( $format ) {
            $url = URLHandler::format($this->createURL($url, $parms));
        } else {
            $url = $this->createURL($url, $parms);
        }
        header("Location: $url");
        X::system()->stop();
    }
    
    /**
     * Get referer url
     * @return string
     */
    public function getReferer( $default='/' ) {
        $url = isset($_SERVER['HTTP_REFERER']) ?  $_SERVER['HTTP_REFERER'] : $default;
        return $url;
    }
    
    /**
     * Go back to prev url by referer.
     * @return void
     */
    public function goBack() {
        $referer = $this->getReferer();
        $url = (null===$referer) ?   '/' : $referer;
        $this->gotoURL($url, null, false);
    }
    
    /**
     * The handler of 404 page.
     * @var string|callable
     */
    protected $E404Content = '404 NOT FOUND';
    
    /**
     * @param unknown $value
     */
    public function assertThrow404( $value ) {
        if ( $value ) {
            $this->throw404();
        }
    }
    
    /**
     * Throw 404 error and exit the script.
     * You can custome the content of 404 page by setting WebAction->E404Content
     * it can be a callable function, a file name or simple string.
     */
    public function throw404() {
        ob_start();
        ob_end_clean();
        
        header('HTTP/1.0 404 NOT FOUND');
        if ( is_callable($this->E404Content) ) {
            call_user_func_array($this->E404Content, array());
        } else if ( is_file($this->E404Content) ) {
            if ( strripos($this->E404Content,'.php') === strlen($this->E404Content)-4 ) {
                require $this->E404Content;
            } else {
                echo file_get_contents($this->E404Content);
            }
        } else {
            echo $this->E404Content;
        }
        
        X::system()->stop();
    }
    
    /**
     * @return boolean
     */
    public function isRequestedByPost() {
        return 'POST' === strtoupper($_SERVER['REQUEST_METHOD']);
    }
    
    /**
     * @param unknown $name
     * @return boolean
     */
    public function hasUploadFile( $name ) {
        return isset($_FILES[$name]);
    }
}