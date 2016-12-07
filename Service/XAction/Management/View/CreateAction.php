<?php 
/* @var $this \X\Service\XAction\Management\Action\CreateAction */
$this->addHeaderItem('<link href="http://cdn.bootcss.com/highlight.js/9.0.0/styles/default.min.css" rel="stylesheet">');
$this->addHeaderItem('<script src="http://cdn.bootcss.com/highlight.js/9.0.0/highlight.min.js"></script>');

$vars = get_defined_vars();
$modules = $vars['modules'];
$moduleName = $vars['moduleName'];
$status = $vars['status'];
$actionName = $vars['actionName'];
$actionExtend = $vars['actionExtend'];
$params = $vars['params'];

$service = $this->getService();
?>

<ol class="breadcrumb">
  <li><a href="<?php echo $this->createURL('index')?>">管理主页</a></li>
  <li><a href="<?php echo $this->createURL('service/index'); ?>">服务管理</a></li>
  <li><a href="<?php echo $this->createURL('service/detail', array('name'=>$service->getName())); ?>"><?php echo $this->encodeHtmlString($service->getPrettyName()); ?></a></li>
  <li class="active">创建行为处理器</li>
</ol>

<strong>创建行为处理器</strong>
<br>
<br>

<?php $actionURL = $this->createURL('service/advance', array('name'=>$service->getName(), 'extaction'=>'createAction')); ?>
<form action="<?php echo $actionURL; ?>" method="post">
  <div class="form-group">
    <label>所属模块</label>
    <select class="form-control" name="moduleName">
      <option value="" <?php if(''===$moduleName): ?>selected<?php endif;?>> 未选择
      <?php foreach ( $modules as $module ) : ?>
        <?php /* @var $module \X\Module\SystemManager\Module */ ?>
        <?php $optionStatus = ($moduleName===$module->getName()) ? 'selected' : ''; ?>
        <option value="<?php echo $module->getName(); ?>" <?php echo $optionStatus;?>>
        <?php echo $this->encodeHtmlString($module->getPrettyName()); ?>
      <?php endforeach;?>
    </select>
  </div>
  
  <div class="form-group">
    <label>动作名称 
      &nbsp;&nbsp;
      <span class="glyphicon glyphicon-comment" 
            data-toggle="popover" 
            data-html="true"
            data-content="Name of action that would be executed. &lt;br&gt;For example: &lt;strong&gt;picture/upload&lt;/strong&gt;"
      ></span>
    </label>
    <input type="text" name="actionName" class="form-control" value="<?php echo $actionName; ?>">
  </div>
  
  <div class="form-group">
    <label>继承于
      &nbsp;&nbsp;
      <span class="glyphicon glyphicon-comment" 
            data-toggle="popover" 
            data-html="true"
            data-content="Name of extended class name, 
                          the default action parent class are:
                          &lt;br&gt;&lt;br&gt; 
                          Web Action: &lt;strong&gt;X\Service\XAction\Core\Handler\WebAction &lt;/strong&gt; 
                          &lt;br&gt;
                          CLI Action: &lt;strong&gt;X\Service\XAction\Core\Handler\CommandAction&lt;/strong&gt;"
      ></span>
    </label>
    <input type="text" name="actionExtend" class="form-control" value="<?php echo $actionExtend; ?>">
  </div>
  
  <div class="form-group">
    <label>参数</label>
    <div class="clearfix">
      <div class="col-md-2"><p>名称</p></div>
      <div class="col-md-10"><p>说明</p></div>
    </div>
    <div id="params-container"></div>
    <br>
    <div class="clearfix">
      <div class="col-md-2"><a href="#" id="add-param">添加参数</a></div>
    </div>
  </div>
  
  <button type="submit" class="btn btn-default">执行初始化</button>
</form>

<br>
<?php if ( null !== $status ): ?>
  <div class="alert alert-<?php echo $status[0]; ?>">
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    <?php echo $this->encodeHtmlString($status[1]); ?>
  </div>
  <?php if('success' === $status[0]): ?>
  <pre><?php echo $status[3]; ?> :
    <code class="php"><?php echo $status[2]; ?></code>
  </pre>
  <script>
  $(document).ready(function() {
    hljs.initHighlighting();
  });
  </script>
  <?php endif;?>
<?php endif; ?>

<script>
function deleteParamBar($this) {
    $($this).parent().parent().remove();
    return false;
}

var index = 0;

function addParamBar(name, comment) {
    var template = $('#param-editor').text();
    template = template.replace(/__INDEX__/g, index);
    template = template.replace(/__NAME__/g, name);
    template = template.replace(/__COMMENT__/g, comment);
    $(template).appendTo('#params-container');
    index++;
}

$(function () {
  <?php foreach ( $params as $param ) : ?>
  addParamBar("<?php echo $this->encodeHtmlString($param['name']); ?>", "<?php echo $this->encodeHtmlString($param['comment']); ?>");
  <?php endforeach; ?>
  
  $('#add-param').click(function() {
    addParamBar('','');
    return false;
  });
  $('[data-toggle="popover"]').popover()
});
</script>

<script type="text/template" id="param-editor">
<div class="clearfix">
<div class="col-md-2"><input type="text" name="params[__INDEX__][name]" class="form-control" value="__NAME__"></div>
<div class="col-md-9"><input type="text" name="params[__INDEX__][comment]" class="form-control" value="__COMMENT__"></div>
<div class="col-md-1"><a href="#" onclick="return deleteParamBar(this);">删除</a></div>
</div>
<br>
</script>