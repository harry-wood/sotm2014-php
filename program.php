<?php
include 'wiki-mirror.inc.php';
$title="State_Of_The_Map_2014";

include 'header.php';
?>
<div id="accomodation" class="row block">
  <div class="col-xs-12 col-sm-12">
    <h1><i class="fa fa-map-marker" style="color:rgba(237, 28, 36, 0.75)"></i>
    Program</h1>
    <div id="" class="row block same-height" style="background:white">
<?php

print wiki_mirror($title);    

print "<hr><a href=\"http://wiki.openstreetmap.org/wiki/".urlencode($title)."\">View this on the OpenStreetMap wiki</a>";

?>
  </div>
</div>
<?php

include 'footer.php';


