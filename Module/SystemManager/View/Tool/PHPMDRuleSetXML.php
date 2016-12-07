<?php 
$vars = get_defined_vars();
$config = $vars['config'];
echo '<?xml version="1.0"?> ';
?>
<ruleset 
  name="System Manager Temp Rule Set" 
  xmlns="http://pmd.sf.net/ruleset/1.0.0" 
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
  xsi:schemaLocation="http://pmd.sf.net/ruleset/1.0.0 http://pmd.sf.net/ruleset_xml_schema.xsd" 
  xsi:noNamespaceSchemaLocation=" http://pmd.sf.net/ruleset_xml_schema.xsd"
> 
  <description>This rule set generate by System Manager Module.</description>
  <php-includepath>/home/michael/Projects/X01.Lunome/source/Module/Dionysos/Configuration/PHPMD/Rule</php-includepath>
  
  <?php foreach ( $config as $ruleSetPath => $ruleSetConfig ) : ?>
  <rule ref="<?php echo $ruleSetPath; ?>">
    <?php foreach ( $ruleSetConfig['disabled'] as $ruleName ) : ?>
    <exclude name="<?php echo $ruleName; ?>" />
    <?php endforeach; ?>
  </rule>
  <?php endforeach; ?>
</ruleset>