<?php 
$vars = get_defined_vars();
$apis = $vars['apis'];
?>
<div class="panel-group" id="api-list-accordion" role="tablist" aria-multiselectable="true">
  <?php $index = 0; ?>
  <?php foreach ( $apis as $key => $api ) : ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading-<?php echo $index; ?>">
      <h4 class="panel-title">
        <a data-toggle="collapse" 
           data-parent="api-list-accordion" 
           href="#doc-<?php echo $index; ?>" 
           aria-expanded="true" 
           aria-controls="collapseOne"
        ><?php echo $key; ?></a>
      </h4>
    </div>
    <div id="doc-<?php echo $index; ?>" 
         class="panel-collapse collapse" 
         role="tabpanel" 
         aria-labelledby="heading-<?php echo $index; ?>"
    >
      <div class="panel-body">
        <p><?php echo $api['description'];?></p>
        <?php if ( !empty($api['params']) ) : ?>
        <div class="row">
          <div class="col-md-1"><strong>参数</strong></div>
          <div class="col-md-11">
            <?php foreach ( $api['params'] as $name => $param ) :?>
            <p>
              <strong><?php echo $name; ?></strong>
              <small class="text-muted">(<?php echo $param['type']; ?>)</small>
              &nbsp;&nbsp;&nbsp;&nbsp;
              <span><?php echo $param['description']; ?></span>
            </p>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php $index ++; ?>
  <?php endforeach; ?>
</div>