<?php
$vars = get_defined_vars();
$apis = $vars['apis'];
?>
<table class="table table-striped table-hover table-condensed">
  <tr>
    <th>名称</th>
    <th>描述</th>
  </tr>
  <?php foreach ( $apis as $api ) : ?>
  <tr>
    <td><?php echo $api['name']; ?></td>
    <td><?php echo $api['description']; ?></td>
  </tr>
  <?php endforeach; ?>
</table>