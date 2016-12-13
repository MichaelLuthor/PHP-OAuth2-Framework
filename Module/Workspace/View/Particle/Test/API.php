<?php
$vars = get_defined_vars();
$apis = $vars['apis'];
?>
<form class="form-horizontal" role="form">
  <div class="form-group">
    <label class="col-md-2 control-label">API</label>
    <div class="col-md-9">
      <select class="form-control" id="slt-api">
      <?php foreach ( $apis as $api ) : ?>
        <option value="<?php echo $api['name'];?>"><?php echo $api['name'];?>
      <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label class="col-md-2 control-label">Parameters</label>
    <div class="col-md-9" id="parameter-container"></div>
  </div>
  <div class="form-group">
    <div class="col-md-offset-2 col-md-10">
      <button type="button" class="btn btn-default" id="btn-call">Call</button>
    </div>
  </div>
  <div class="form-group">
     <label class="col-md-2 control-label">Response</label>
    <div class="col-md-9">
      <pre id="api-result-container"></pre>
    </div>
  </div>
</form>
<script type="text/template" id="tpl-parameter">
<div class="row">
  <div class="col-md-2">
    <p class="form-control-static">__NAME__</p>
  </div>
  <div class="col-md-5">
    <input 
      type="text" 
      value="__DEFAULT__" 
      class="form-control api-param" 
      placeholder="__TYPE__"
      name="__NAME__"
    >
  </div>
  <div class="col-md-5">
    <p class="form-control-static">
      <small class="text-muted">__DESCRIPTION__</small>
    </p>
  </div>
  <br><br>
</div>
</script>
<script>
$(document).ready(function() {
/** API数据 */
var apiData = <?php echo json_encode($apis); ?>;

/** 测试API变更 */
$('#slt-api').change(function() {
  $('#parameter-container').empty();
  var params = apiData[$(this).val()].params;
  var template = $('#tpl-parameter').text();
  for ( var i in params ) {
    var param = template
      .replace(/__NAME__/g, i)
      .replace(/__TYPE__/, params[i].type)
      .replace(/__DEFAULT__/, (null==params[i]['default'] ? '' : params[i]['default']))
      .replace(/__DESCRIPTION__/, params[i].description);
    $('#parameter-container').append(param);
  }
}).trigger('change');

/** call button */
$('#btn-call').click(function() {
  var params = {};
  var paramContainer = $('.api-param');
  for ( var i=0; i<paramContainer.length; i++ ) {
    params[paramContainer.eq(i).attr('name')] = paramContainer.eq(i).val(); 
  }
  
  $.post('/index.php?module=Workspace&action=Test/APICall', {
    'name'   : $('#slt-api').val(),
    'params' : params,
  }, function( response ) {
    $('#api-result-container').text(response);
  }, 'text');
});
});
</script>