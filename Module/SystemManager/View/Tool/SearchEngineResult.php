<?php 
$vars = get_defined_vars();
$result = $vars['result'];
$keyword = $vars['keyword'];
?>
<div style="background-color:#F6F6F6;padding:15px;">
  <img src="/Assets/SystemManager/img/google.png">
  &nbsp;
  <input 
    type="text" style="width:546px;height:35px;border:1px solid #D5D2D2;padding:0px 10px;"
    value="<?php echo $keyword; ?>"
  ><button style="height:35px;border:0px none;width:40px;background-color:#546FFF;color:#fff;">
    <span class="glyphicon glyphicon-search"></span>
  </button>
</div>
<div style="line-height: 50px;padding-left: 115px;">
  <div style="display: inline;padding: 15px;border-style: none none solid none;border-color: #00A5FF;color: #00A5FF;font-weight: bold;">All</div>
  <div style="display: inline;padding: 12px;color: #929292;">Books</div>
  <div style="display: inline;padding: 12px;color: #929292;">Images</div>
  <div style="display: inline;padding: 12px;color: #929292;">Videos</div>
  <div style="display: inline;padding: 12px;color: #929292;">News</div>
  <div style="display: inline;padding: 12px;color: #929292;">
    More
    <span class="glyphicon glyphicon-triangle-bottom" style="font-size: 0.7em;"></span>
  </div>
  <div style="display: inline;padding: 12px;color: #929292;">Search Tools</div>
</div>
<hr style="margin:0;">
<div style="color: #7D7979;margin: 10px 0px 20px 117px;">About <?php echo $vars['count']; ?> results (0.38 seconds)</div>

<div style="padding-left: 115px;width: 680px;">
  <?php foreach ( $result as $item ) : ?>
  <div style="margin-bottom: 20px;">
    <a 
      target="_blank" 
      href="<?php echo $item['link']; ?>" 
      style="color: #2D0AE0;font-size: 1.2em;"
      ><?php echo str_replace($keyword, "<span style=\"color:#DD0E0E;\">$keyword</span>", $item['title']); ?></a><br>
    <span style="color: #017703;"><?php echo $item['link']; ?></span>
    <div class="dropdown" style="display:inline">
      <button class="btn btn-default dropdown-toggle btn-link" type="button" data-toggle="dropdown" style="padding: 0;"><span class="caret"></span>Tools</button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <li><a href="/index.php?module=systemManager&action=tool/SearchEngineSimulator/RefreshCache&link=<?php echo $item['link']; ?>">Refresh Cache</a></li>
        <li><a href="/index.php?module=systemManager&action=tool/SearchEngineSimulator/DeleteCache&link=<?php echo $item['link']; ?>">Delete Cache</a></li>
      </ul>
    </div>
    <br>
    <span><?php echo str_replace($keyword, "<span style=\"color:#DD0E0E;\">$keyword</span>", $item['description']); ?></span>
  </div>
  <?php endforeach; ?>
  
  <?php 
  $count = $vars['count']; 
  $current = $vars['current'];
  $size = $vars['size'];
  $page = $current;
  ?>
  <hr style="margin:0;">
  <div style="text-align: center;padding: 20px 0;font-size: 0.9em;margin-bottom: 60px;">
    <a href="/index.php?module=systemManager&action=tool/SearchEngineSimulator/Index&page=<?php echo $current-1; ?>" style="margin-right: 15px;">Previous</a>
    <a href="/index.php?module=systemManager&action=tool/SearchEngineSimulator/CleanCache" style="margin-right: 15px;">Clean Cache</a>
    <a href="/index.php?module=systemManager&action=tool/SearchEngineSimulator/Index&page=<?php echo $current+1; ?>" style="margin: 0px 15px;">Next</a>
  </div>
</div>