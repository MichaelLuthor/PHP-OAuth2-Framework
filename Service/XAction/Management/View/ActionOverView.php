<?php
/* @var $this \X\Service\XAction\Management\Action\CreateAction */
$this->addHeaderItem('<link href="http://cdn.bootcss.com/jstree/3.2.1/themes/default/style.min.css" rel="stylesheet">');
$this->addHeaderItem('<script src="http://cdn.bootcss.com/jstree/3.2.1/jstree.min.js"></script>');

$this->addHeaderItem('<link href="http://cdn.bootcss.com/highlight.js/9.0.0/styles/default.min.css" rel="stylesheet">');
$this->addHeaderItem('<script src="http://cdn.bootcss.com/highlight.js/9.0.0/highlight.min.js"></script>');

$vars = get_defined_vars();
$actions = $vars['actions'];

$service = $this->getService();
?>

<ol class="breadcrumb">
  <li><a href="<?php echo $this->createURL('index')?>">管理主页</a></li>
  <li><a href="<?php echo $this->createURL('service/index'); ?>">服务管理</a></li>
  <li><a href="<?php echo $this->createURL('service/detail', array('name'=>$service->getName())); ?>"><?php echo $this->encodeHtmlString($service->getPrettyName()); ?></a></li>
  <li class="active">行为总览</li>
</ol>

<strong>行为总览</strong>
<br>
<br>
<div class="col-md-3">
  <div id="action-tree"></div>
  <br>
  <br>
  <br>
</div>
<div class="col-md-9" >
  <p>Path: <span id="action-file-path"></span></p>
  <pre><code id="action-file-content" class="php"></code></pre>
</div>
<script type="text/javascript">
$(document).ready(function() {
    var viewURL = '<?php echo $this->createURL('service/advance', array('name'=>$service->getName(), 'extaction'=>'actionFileView')); ?>';
    var createURL = '<?php echo $this->createURL('service/advance', array('name'=>$service->getName(), 'extaction'=>'createAction')); ?>';
    
    $('#action-tree').jstree({ 'core' : {
        'data' : <?php echo json_encode($actions); ?>
    } });
    
    $('#action-tree').on('select_node.jstree', function (e, data) {
        if ( 'undefined' == typeof(data.node.a_attr['data-type']) ) {
            return;
        }
        
        var type = data.node.a_attr['data-type'];
        if ( 'action' == type ) {
            $.get(viewURL, {path:data.node.a_attr['data-path']}, function(response) {
                $('#action-file-content').text(response.content);
                $('#action-file-path').text(response.path);
                $('pre code').each(function(i, block) {
                    hljs.highlightBlock(block);
                  });
            }, 'json');
        } else if ( 'action-create' == type ) {
            var url = createURL+"&moduleName="+data.node.a_attr['data-module']+"&actionName="+data.node.a_attr['data-action-prefix']+"&save=false";
            window.location.href = url;
        } else {
            
        }
    });
});
</script>
