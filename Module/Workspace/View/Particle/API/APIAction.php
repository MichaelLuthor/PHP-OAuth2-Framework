<?php 
$vars = get_defined_vars();
$name = $vars['name'];
$params = $vars['param'];
$author = $vars['author'];
$namespace = $vars['namespace'];
$description = $vars['description'];

$paramList = array();
foreach ( $params as $param ) {
    $defaultValue = '';
    if ( !empty($param['default']) ) {
        $defaultValue = '='.$param['default'];
    }
    $paramList[] = '$'.$param['name'].$defaultValue;
}
if ( !empty($namespace) ) {
    $namespace = '\\'.$namespace;
}
?>
namespace X\Module\API\Action<?php echo $namespace; ?>;
use X\Core\X;
use X\Service\XAction\Core\Handler\WebAction;
use X\Service\OAuth2\Service as OAuth2Service;
/**
 * <?php echo $description; ?> 
 * @author <?php echo $author; ?> 
 */
class <?php echo $name; ?> extends WebAction {
    /**<?php foreach ( $params as $param ) : ?> 
     * @param <?php echo $param['type']; ?> $<?php echo $param['name']; ?> <?php echo $param['description']; ?>
     <?php endforeach;?> 
     *
     * {@inheritDoc}
     * @see \X\Service\XAction\Core\Util\Action::runAction()
     */
    public function runAction( <?php echo implode(', ', $paramList);?> ) {
      
    }
}