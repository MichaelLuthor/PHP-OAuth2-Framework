<?php 
$vars = get_defined_vars();
$errors = $vars['errors'];
?>
<div class="col-md-offset-4 col-md-4">
  <br><br><br><br>
  <form role="form" action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">
    <div class="form-group">
      <label>Account</label>
      <input type="text" class="form-control" name="form[account]">
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" class="form-control" name="form[password]">
    </div>
    <button type="submit" class="btn btn-default btn-block" name="form[authorize]" value="yes">Login</button>
    
    <br>
    <?php if ( !empty($errors) ) : ?>
      <div class="alert alert-danger">
        <?php echo implode("<br>", $errors); ?>
      </div>
    <?php endif; ?>
  </form>
</div>