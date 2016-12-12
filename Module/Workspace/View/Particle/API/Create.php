<?php
$vars = get_defined_vars();
$error = $vars['error'];
$form = $vars['form'];
?>
<?php if ( null !== $error ): ?>
<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
<br>
<?php endif;?>
<form 
  class="form-horizontal" 
  role="form" 
  action="/index.php?module=Workspace&action=API/Create" 
  method="post"
>
  <div class="form-group">
    <label for="inputEmail3" class="col-md-2 control-label">Name</label>
    <div class="col-md-10">
      <input 
        type="text" 
        class="form-control" 
        placeholder="example:User\Register"
        name="form[name]"
        value="<?php echo $form['name']; ?>"
      >
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-md-2 control-label">Parameters</label>
    <div class="col-md-9">
      <div id="api-parameter-container"></div>
      <div>
        <button class="btn btn-default" type="button" id="btn-parameter-add">
          <span class=" glyphicon glyphicon-plus"></span>
        </button>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-md-2 control-label">Description</label>
    <div class="col-md-10">
      <textarea 
        class="form-control"
        name="form[description]"
      ><?php echo $form['description']; ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-md-offset-2 col-md-10">
      <button type="submit" class="btn btn-default">Generate</button>
    </div>
  </div>
</form>
<script type="text/template" id="tpl-api-create-parameter">
<div class="row">
  <div class="col-md-2">
    <input 
      type="text" 
      class="form-control" 
      placeholder="name" 
      name="form[param][__INDEX__][name]"
      value="__NAME__"
    >
  </div>
  <div class="col-md-2">
    <select 
      class="form-control" 
      placeholder="type" 
      name="form[param][__INDEX__][type]"
    >
      <option value="integer" __TYPE_INTEGER__>Integer
      <option value="float" __TYPE_FLOAT__>Float
      <option value="string" __TYPE_STRING__>String
    </select>
  </div>
  <div class="col-md-2">
    <input 
      type="text" 
      class="form-control" 
      placeholder="default value" 
      name="form[param][__INDEX__][default]"
      value="__DEFAULT__"
    >
  </div>
  <div class="col-md-5">
    <input 
      type="text" 
      class="form-control" 
      placeholder="description" 
      name="form[param][__INDEX__][description]"
      value="__DESCRIPTION__"
    >
  </div>
  <div class="col-md-1">
    <button class="btn btn-danger btn-param-del" type="button">
      <span class="glyphicon glyphicon-trash"></span>
    </button>
  </div>
  <br>
  <br>
</div>
</script>
<script>
jQuery(function() {
/** Parameter index */
var paramIndex = 0;
  
/** Delete parameter hadnler */
function paramDelete() {
  $(this).unbind('click');
  $(this).parent().parent().remove();
}

/* Add parameter handler */
function paramAdd( config ) {
  var template = $('#tpl-api-create-parameter').text()
    .replace(/__INDEX__/g, paramIndex)
    .replace(/__NAME__/g, config.name)
    .replace(/__TYPE_INTEGER__/g, ('integer'==config.type) ? 'selected' : '')
    .replace(/__TYPE_FLOAT__/g, ('float'==config.type) ? 'selected' : '')
    .replace(/__TYPE_STRING__/g, ('string'==config.type) ? 'selected' : '')
    .replace(/__DEFAULT__/g, config['default'])
    .replace(/__DESCRIPTION__/g, config.description);
  $('#api-parameter-container').append(template);
  $('.btn-param-del').unbind('click').click(paramDelete);
  paramIndex ++;
}

<?php foreach ( $form['param'] as $param ) : ?>
paramAdd(<?php echo json_encode($param); ?>);
<?php endforeach; ?>

/* Add parameter */
$('#btn-parameter-add').click(function() {
  paramAdd({name:'',description:'',type:'','default':''});
});
});
</script>