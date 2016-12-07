<?php
namespace X\Module\SystemManager\Action\Tool;
use X\Module\SystemManager\Util\Action;
class BootstrapThemeGeneratorSaveCSS extends Action {
    /**
     * {@inheritDoc}
     * @see \X\Module\SystemManager\Util\Action::run()
     */
    public function run() {
        $css = $this->getParam('data', '');
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename="bootstrap-theme.txt"');
        echo $css;
    }
}