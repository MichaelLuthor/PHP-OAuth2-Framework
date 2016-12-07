<br>
<br>
<br>
<div class="row">
<div class="col-md-offset-4 col-md-4">
<h1>登录</h1>
<hr>
<?php if ( isset($_POST['form']) ) : ?>
  <div class="alert alert-danger" role="alert">登录失败。</div>
<?php endif; ?>
<form action="/index.php?module=systemManager&action=login" method="post">
  <div class="form-group">
    <label>账号</label>
    <input type="text" class="form-control" name="form[account]">
  </div>
  
  <div class="form-group">
    <label>Password</label>
    <input type="password" class="form-control" name="form[password]">
  </div>
  <button type="submit" class="btn btn-default">登录</button>
</form>
</div>
</div>
