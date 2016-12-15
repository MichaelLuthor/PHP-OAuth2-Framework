<?php
$vars = get_defined_vars();
$apis = $vars['apis'];
?>
<?php foreach ( $apis as $api ) : ?>
  <?php echo $api['name']; ?>
  <?php echo $api['description']; ?>
  
    Parameters:
    <?php if ( !empty($api['params']) ): ?>
     <?php foreach ( $api['params'] as $name => $param ) : ?>
         <?php echo $name; ?> (<?php echo $param['type'];?>) 
         Default : <?php echo $param['default'];?> 
         <?php echo $param['description'];?>
     <?php endforeach; ?>
    <?php else:?>
    (none)
    <?php endif;?>
<?php endforeach; ?>
