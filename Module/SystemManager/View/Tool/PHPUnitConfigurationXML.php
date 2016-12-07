<?php 
$vars = get_defined_vars();
$bootstrap = $vars['bootstrap'];
$loggingPath = $vars['loggingPath'];
?>
<phpunit
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:noNamespaceSchemaLocation="http://schema.phpunit.de/5.2/phpunit.xsd"
  bootstrap="<?php echo $bootstrap; ?>"
>
  <logging>
  <log type="coverage-html" target="<?php echo $loggingPath; ?>/report" lowUpperBound="35" highLowerBound="70"/>
  <log type="coverage-clover" target="<?php echo $loggingPath; ?>/coverage.xml"/>
  <log type="coverage-php" target="<?php echo $loggingPath; ?>/coverage.serialized"/>
  <log type="coverage-text" target="<?php echo $loggingPath; ?>/coverage.txt" showUncoveredFiles="false"/>
  <log type="json" target="<?php echo $loggingPath; ?>/logfile.json"/>
  <log type="tap" target="<?php echo $loggingPath; ?>/logfile.tap"/>
  <log type="junit" target="<?php echo $loggingPath; ?>/logfile.xml" logIncompleteSkipped="false"/>
  <log type="testdox-html" target="<?php echo $loggingPath; ?>/testdox.html"/>
  <log type="testdox-text" target="<?php echo $loggingPath; ?>/testdox.txt"/>
  </logging>
</phpunit>