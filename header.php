<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $friendly_title; ?></title>
    <meta content="authenticity_token" name="csrf-param" />
    <meta content="ony7bwrP2z5d+Dq4fx9Tl/NlpPOOGGqxtOtzvq9R24s=" name="csrf-token" />
    <meta property="og:type" content="event" />
    <meta property="og:url" content="http://www.stateofthemap.org" />
    <meta property="og:start_time" content="2014-11-07T00:00:00+00:00" />
    <meta property="og:description" content="7th-9th Nov, Buenos Aires" />
    <meta property="og:title" content="State of the Map 2014" />
    <meta property="og:image" content="http://2014.stateofthemap.org/assets/logo_full.png" />
    <meta property="og:locale" content="en_GB" />
    <meta property="og:site_name" content="State of the Map" />
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
    <![endif]-->

    <link rel="shortcut icon" href="./assets/favicon.ico" type="image/x-icon" />
    
    <link href="./assets/application.css" media="all" rel="stylesheet" />
    <link href="./assets/font-awesome.css" media="all" rel="stylesheet" />
    
    <link href="./assets/leaflet.css" media="all" rel="stylesheet" />
   
    <link rel="stylesheet" type=text/css href="./style.css">
    
    <script src="./assets/leaflet.js"></script>
    <script>
//Init the page (maps)
function init() {
  var artmap, latlng, map, marker, tiles;

  latlng = [-34.59359, -58.38327];

  
  if (document.getElementById("venuemap")) { 
    map = L.map('venuemap', {                    
      scrollWheelZoom: false
    }).setView(latlng, 15);
    
    L.tileLayer('http://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 18,
    }).addTo(map);
    
    marker = L.marker(latlng).addTo(map);
    marker.bindPopup(venueAddress).openPopup();
  }

  //Arty background map
  tiles = ['http://a.tiles.mapbox.com/v3/fernando.ikdi0ih7/{z}/{x}/{y}.png'];
  artmap = L.map('artmap', {
    scrollWheelZoom: false
  }).setView([-34.57158, -58.43926], 12);
  L.tileLayer(tiles[Math.floor(Math.random() * tiles.length)], {
    maxZoom: 12
  }).addTo(artmap);
  
  
  //webkit fix http://stackoverflow.com/a/9513843/338265
  document.body.style.webkitTransform = 'scale(1)';
}
    </script>
    
  </head>

<body onLoad="init();">

<div id="artmap" class="hidden-xs"></div>

<div class="navbar navbar-fixed-top hidden-xs" role="navigation">
  <div class="collapse navbar-collapse">
    <ul class="nav navbar-nav navbar-right">
      <li><a href="./index.php">Home</a></li>
      <li><a href="./about.php">About</a></li>
      <li><a href="./venue.php">Venue etc</a></li>
      <li><a href="./program.php">Program</a></li>
      <li>&nbsp;&nbsp;&nbsp;&nbsp;</li>
    </ul>
  </div>
</div>

<div class="navbar visible-xs navbar-fixed-bottom" role="navigation">
  <div class="navbar-header">
    <button type="button" class="btn navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
    <i class="fa fa-bars fa-2x" style="color:white"></i>
    </button>
  </div>
  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav navbar-right">
      <li><a href="./index.php">Home</a></li>
      <li><a href="./about.php">About</a></li>
      <li><a href="./venue.php">Venue etc</a></li>
      <li><a href="/accomodation">Accomodation</a></li>
    </ul>
  </div>
</div>


