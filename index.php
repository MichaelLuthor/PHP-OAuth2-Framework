<?php
require 'Core/X.php';
/**
 * Start x framework and set current directory as root path.
 */
X\Core\X::start(dirname(__FILE__))->run();