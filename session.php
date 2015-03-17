<?php
include 'wiki-mirror.inc.php';

if (isset($_GET["title"])) $title=$_GET["title"];
if ($title=="") die('title param required');
$title = "SotM_2014_session:_" . $title;

$content = wiki_mirror($title);    

include 'header.php';
?>
<div class="row block">
  <div class="col-xs-12 col-sm-12">
  <h1 class=""><i class="fa fa-map-marker" style="color:rgba(237, 28, 36, 0.75)"></i>
  <?php echo htmlspecialchars(friendly_title($title)); ?></h1>
    
    <div id="" class="row block same-height" style="background:white">
<?php
print $content;

print "<hr><a href=\"http://wiki.openstreetmap.org/wiki/".urlencode($title)."\">View this on the OpenStreetMap wiki</a>";

?>
  </div>
</div>
<?php

include 'footer.php';


