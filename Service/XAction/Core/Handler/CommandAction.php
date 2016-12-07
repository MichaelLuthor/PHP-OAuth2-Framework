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
/**
 * @author  Michael Luthor <michael.the.ranidae@gmail.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
abstract class CommandAction extends \X\Service\XAction\Core\Util\Action {
    /**
     * Quit action
     */
    public function quit() {
        X::system()->stop();
    }
    
    /**
     * Write string with new line mark to stdout.
     * @param unknown $string
     */
    public function writeLine( $string ) {
        $args = func_get_args();
        $args[0] .= "\n";
        return call_user_func_array(array($this, 'write'), $args);
    }
    
    /**
     * Write string to stdout.
     * @param unknown $string
     */
    public function write( $string ) {
        if ( 1 == func_num_args() ) {
            echo $string;
        } else {
            call_user_func_array('printf', func_get_args());
        }
    }
}