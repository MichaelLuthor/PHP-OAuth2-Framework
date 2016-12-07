<?php
$vars = get_defined_vars();
$result = $vars['result'];
$target = $vars['target'];
$targetDetailPage = $vars['targetDetailPage'];
?>
<h4>重复代码检查: 
  <a href="<?php echo $targetDetailPage; ?>"><?php echo $this->encodeHtmlString($target->getPrettyName()); ?></a>
</h4>
<?php foreach ( $result as $item ) : ?>
<div class="panel panel-warning">
  <div class="panel-heading">
    <?php foreach ( $item['files'] as $file ) : ?>
    <?php echo $file['path']; ?> : #<?php echo $file['line']; ?><br>
    <?php endforeach; ?>
  </div>
  <div class="panel-body">
  <pre><code><?php echo $item['code']; ?></code></pre>
  </div>
</div>
<?php endforeach; ?>