<?php
//Set $CACHEDIR to a writable file path e.g. "/tmp/contentchace"
//or to 'false' to disabling file caching (all requests proxied)
$CACHEDIR = false;

$CACHEONLY = false; //If you want to disable all new wiki scraping

$CACHE_TIMEOUT = 60 * 10; //in seconds

$allowedPrefixes = array(
  "SotM_2014_session:");

$sessionListPages = array(
  "State_Of_The_Map_2014");

function chompleft(&$content, $needle, $required=true) {
  $pos = strpos($content, $needle);
  if ($pos===FALSE && $required) die("'" . htmlspecialchars($needle) . "' not found");
  if ($pos!==FALSE) $content = substr($content, $pos + strlen($needle), strlen($content) );
}
function chompright(&$content, $needle, $required=true) {
  $pos = strpos($content, $needle);
  if ($pos===FALSE && $required) die("'" . htmlspecialchars($needle) . "' not found");
  if ($pos!==FALSE) $content = substr($content, 0, $pos );
}
function startsWith($haystack, $needle) {
  return !strncmp($haystack, $needle, strlen($needle));
}
function get_url($url) {
  if (function_exists('curl_version')) {
    $content = get_curl_style($url);
  } else {
    $content = get_filecontent_style($url);
  }

  if ($content===FALSE) die("Failed to get page: <a href='$url'>$url</a>");
  if (strlen($content)==0) die("Empty string fetching url $url");
  if (strlen($content)<100) die("Content too short $url");
  if (strpos($content, 'There is currently no text in this page') !== FALSE) die("Wiki page doesn't exist");
  return $content;
}
function get_curl_style($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url );
  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec($ch);
  curl_close($ch);

  return $data;
}

function get_filecontent_style($url) {
  $user_agent = "Harry's wiki scraping script, PHP";
  $options  = array('http' => array('user_agent' => $user_agent ));
  $context  = stream_context_create($options);
  $response = file_get_contents($url, false, $context);
  return $response;
}

function page_type($title) {
  global $allowedPrefixes, $sessionListPages;
  $pageType = "DISALLOW";
  foreach ($sessionListPages as $allowedTitle) {
    if ($title==$allowedTitle) $pageType = "SESSIONLIST";
  }
  foreach ($allowedPrefixes as $prefix) {
    if (startsWith($title, $prefix)) $pageType = "SESSION";
  }
  return $pageType;
}

function friendly_title($title) {
  global $allowedPrefixes;
  $pageType = page_type($title);
  if ($pageType =="SESSIONLIST") {
    return "Program";
  } else {
    $friendly_title = $title;
    foreach ($allowedPrefixes as $prefix) {
      if (startsWith($title, $prefix)) {
        $friendly_title = str_replace($prefix,"Session: ",$friendly_title);
      }
    }
    $friendly_title = str_replace("_"," ",$friendly_title);
    return $friendly_title;
  }
}

// Main function called in-page
// This has the cache wrapping logic before calling wiki_mirror_content
function wiki_mirror($title) {
  global $CACHEDIR, $CACHE_TIMEOUT, $CACHEONLY, $allowedPrefixes, $sessionListPages;

  $error = "";

  $urlTitle = urlencode($title);
  $urlTitle = str_replace("%2F", "/", $title);

  $url = "http://wiki.openstreetmap.org/wiki/" . $urlTitle . "?script=harrys-SOTM-scraper";

  if ($CACHEDIR!==false) {
    $fileTitle = str_replace("/", "--", $title);
    $file = $CACHEDIR . $fileTitle . ".html";

    if (!file_exists($CACHEDIR)) mkdir($CACHEDIR, 0777, true);
  }

  $time_start = microtime(true);

  if ($CACHEDIR===false || !file_exists($file) || filesize($file)==0 ||  time()- filemtime($file) > $CACHE_TIMEOUT ) {
    print "<!-- CALLING URL $url -->\n";

    $content = get_url($url);

    $pageType = page_type($title);
    if ($pageType=="DISALLOW") die("Bad wiki title. We're not mirroring that");

    if ($CACHEONLY==true) die("Sorry. Wiki scraping disabled. The URL we would've tried is $url");

    $content = wiki_mirror_content($content, $pageType);

    if ($CACHEDIR!==false) {
      //write cache file
      file_put_contents($file, $content);
    }
  } else {
    print "<!-- GETTING FROM CACHE $file -->\n";
    $content = file_get_contents($file);
  }

  $time = microtime(true) - $time_start;
  print "<!-- Got content in $time seconds -->\n";

  return $content;
}

// Main mirror logic
// Pass raw HTML as scraped from the wiki.
function wiki_mirror_content($content, $pageType) {
  global $allowedPrefixes, $sessionListPages;
  chompleft($content, "class=\"mw-content-ltr\">");

  chompright($content, "<div class=\"printfooter\">");

  if ($pageType =="SESSIONLIST") {
    $possibleHeadings = array("Sessions", "Sessions list", "Program", "Programme", "Conference Program");

    $found=false;
    foreach ($possibleHeadings as $heading) {
      $startheading = "<span class=\"mw-headline\" id=\"" . str_replace(" ","_",$heading) . "\">$heading</span>";
      if (strpos($content,$startheading)!==FALSE) {
        chompleft($content, $startheading, false);
        $found=true;
      }
    }
    if ($found==false) die("Missing sessions heading");

    //Following after the span there's a h3 tag (or different level) remove it
    if (substr($content, 2,1)!="h") die("expected h tag");

    $htag = "<" . substr($content, 2, 3);
    $content = substr($content, 5, strlen($content) );

    chompright($content, $htag, false); //And look for the same level heading to end on

    // Check for (optional) better things to right trim down to
    chompright($content, "<br />\n<br />", false);
    chompright($content, "<div style=\"text-align:center; clear:both; border: 2px solid black;\">", false );

  } else if ($pageType == "SESSION") {

    //Remove green box panel if present
    $panelStart = "border:solid; border-width:2px; background-color:#E0EEE0; border-color:#666666;\">";
    $panelpos = strpos($content, $panelStart);
    if ($panelpos>0) {
      chompleft($content, $panelStart);
      chompleft($content, "</table>");
    }
  }

  //Lose the rel=nofollows. Assume we're spam free and let's give some google love
  $content = str_replace(" rel=\"nofollow\"", "", $content);

  //Add target="_top" for all links. This gets undone below for the internal links
  $content = str_replace(" href=\"",    " target=\"_top\" href=\"", $content);

  //Change links to stay here on the scraper, for any link to a wiki pages which we are mirroring
  foreach ($allowedPrefixes as $prefix) {
    $content = str_replace(" target=\"_top\" href=\"/wiki/" . $prefix . "_", " href=\"./session.php?title=", $content);
  }

  //All other wiki pages...  link the actual wiki
  $content = str_replace(" href=\"/wiki/",    " href=\"http://wiki.openstreetmap.org/wiki/", $content);
  $content = str_replace(" href=\"/w/",       " href=\"http://wiki.openstreetmap.org/w/", $content);
  $content = str_replace(" src=\"/w/images/", " src=\"http://wiki.openstreetmap.org/w/images/", $content);

  return $content;
}
