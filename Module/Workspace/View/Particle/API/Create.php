<?php
$vars = get_defined_vars();
?>
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
      ></textarea>
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
    >
  </div>
  <div class="col-md-2">
    <select 
      class="form-control" 
      placeholder="type" 
      name="form[param][__INDEX__][type]"
    >
      <option>Integer
      <option>Float
      <option>String
    </select>
  </div>
  <div class="col-md-2">
    <input 
      type="text" 
      class="form-control" 
      placeholder="default value" 
      name="form[param][__INDEX__][default]"
    >
  </div>
  <div class="col-md-5">
    <input 
      type="text" 
      class="form-control" 
      placeholder="description" 
      name="form[param][__INDEX__][description]"
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

/* Add parameter */
$('#btn-parameter-add').click(function() {
  var template = $('#tpl-api-create-parameter').text().replace(/__INDEX__/g, paramIndex);
  $('#api-parameter-container').append(template);
  $('.btn-param-del').unbind('click').click(paramDelete);
  paramIndex ++;
});
});
</script>