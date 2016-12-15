<?php
$vars = get_defined_vars();
$apis = $vars['apis'];
?>
<?php foreach ( $apis as $api ) : ?>
  <h3><?php echo $api['name']; ?></h3>
  <div><?php echo $api['description']; ?></div>
  
  <div>
    Parameters: <br></br>
    <?php if ( !empty($api['params']) ): ?>
     <?php foreach ( $api['params'] as $name => $param ) : ?>
       <span>
         <?php echo $name; ?> (<?php echo $param['type'];?>) 
         Default : <?php echo $param['default'];?> 
         <?php echo $param['description'];?>
       </span>
     <?php endforeach; ?>
    <?php else:?>
    (none)
    <?php endif;?>
  </div>
<?php endforeach; ?>
