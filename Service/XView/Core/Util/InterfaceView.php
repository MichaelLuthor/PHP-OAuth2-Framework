<?php
/**
 * Namespce defination
 */
namespace X\Service\XView\Core\Util;

/**
 * The view interface.
 * @author  Michael Luthor <michaelluthor@163.com>
 * @version 0.0.0
 * @since   Version 0.0.0
 */
interface InterfaceView {
    /**
     * Display the content of current view to output.
     * @return void
     */
    public function display();
}