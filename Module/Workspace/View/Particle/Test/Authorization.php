<?php 
$vars = get_defined_vars();
$clientID = $vars['clientID'];
?>
<iframe 
  src="/index.php?module=OAuth2&action=Authorize&client_id=<?php echo $clientID; ?>&response_type=code&&state=xyz"
  height="500px"
  width="100%"
  <?php echo 'frameborder="0"'; ?>
></iframe>