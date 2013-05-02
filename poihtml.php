<?php 

$endln = "\n";
$name = $poi->labels[0]->getValue();
$myid = $poi->getMyId();

$html = "";

$html .= '<!DOCTYPE html>' . $endln;
$html .= '<html xmlns:fb="http://ogp.me/ns/fb#">' . $endln;
$html .= '  <head>' . $endln;
$html .= '	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">' . $endln;
$html .= '	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">' . $endln;
$html .= '	<meta name="apple-mobile-web-app-capable" content="yes">' . $endln;
// Set title and add Facebook OpenGraph meta tags
$html .= '	<meta property="og:title" content="' . $name . '" />' . $endln;
$html .= '	<meta property="og:type" content="landmark"/>' . $endln;
$html .= '	<meta property="og:url" content="http://' . $_SERVER['SERVER_NAME'] . "/pois/" . $myid . '"/>' . $endln;
$html .= '	<meta property="og:site_name" content="OpenPOIs"/>' . $endln;

$loc = $poi->getLocation();
if ( !empty($loc) ) {
  $lon = $loc->getX();
  $lat = $loc->getY();
  $html .= '	<meta property="openpois:location:latitude"  content="' . $lat . '"/>' . $endln;
  $html .= '	<meta property="openpois:location:longitude"  content="' . $lon . '"/>' . $endln;
}
$html .= '	<meta property="fb:app_id" content="473919912642476"/>' . $endln;
$html .= '	<title>OpenPOIs Atlas - ' . $name . '</title>' . $endln;
$html .= '	<link type="text/css" rel="stylesheet" href="/css/MyFontsWebfontsKit.css">' . $endln;
$html .= '    <link type="text/css" rel="stylesheet" href="/css/style.css">' . $endln;
$html .= '    <link type="text/css" rel="stylesheet" href="/css/poi.css">' . $endln;
$html .= '	<script src="/js/jquery-1.7.1.min.js"></script>' . $endln;
$html .= '  <script src="/js/login.js"></script>' . $endln;
$html .= '  <script src="/js/poihtml.js"></script>' . $endln;
$html .= '  </head>' . $endln;
$html .= '<body>' . $endln;

//// Facebook stuff
$html .= '  <div id="fb-root"></div>';
$html .= '  <script>(function(d, s, id) {';
$html .= '    var js, fjs = d.getElementsByTagName(s)[0];';
$html .= '    if (d.getElementById(id)) return;';
$html .= '    js = d.createElement(s); js.id = id;';
$html .= '    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=473919912642476";';
$html .= '    fjs.parentNode.insertBefore(js, fjs);';
$html .= '  }(document, \'script\', \'facebook-jssdk\'));</script>';

$html .= '	<div id="banner">' . $endln;

//// Authentication 
include_once('loginout.php');
$thispoiurl = $baseurl . '/' . $myid;
$html .= getButton($thispoiurl);

$html .= '		<span id="title">OpenPOIs Registry</span>' . $endln;
$html .= '		<span id="sub">the hub of location data on the web</span>' . $endln;
$html .= '	</div>' . $endln;
echo $html; // END intro

//// section variables
$messages = '		<div id="messages"></div>' . $endln;
$poiname = '		<div id="poiname"><p class="headline" itemprop="name">' . $name . '</p></div>' . $endln;
$poiitems = getRepresentations($poi);
$poilocation = '<div id="poilocation" class="poiinfosection"'
              . ' itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">' . $endln 
              . '<p class="subhead">location: <span itemprop="latitude">' . $lat
              . '</span>, <span itemprop="longitude">' . $lon . '</span></p></div>' . $endln;
$poidescriptions = getDescriptions($poi);
$tagsarea = getTags($poi);
$placesarea = getRelatedPlaces($poi);
$nearbyplacesarea = getNearbyPlaces($myid, $lat, $lon);
$pictures = '		<div id="pictures" class="poiinfosection"></div>' . $endln;

//// print it all out
echo '	<div id="data" itemscope itemtype="http://schema.org/Place">' . $endln;
echo $messages;
echo $poiname;
echo $poiitems;
echo $poilocation;
//// ADD ADDRESS
echo $poidescriptions;
echo '		<div id="tagsandplaces">' . $endln;
echo $tagsarea;
echo $placesarea;
echo $nearbyplacesarea;
//// ADD RELATIONSHIPS
echo '		</div>' . $endln;
echo $pictures;
echo '  </div>' . $endln;
echo '  <p>&nbsp;</p>' . $endln;

//// footer stuff
echo '    <p><!-- end bars --></p>' . $endln;
echo '    <div id="footer">' . $endln;
echo '    	<a href="/">Home</a> | <a href="api.html">API</a> | ' . $endln;
echo '        <a href="faq.html">FAQ</a> | ' . $endln;
echo '        <a href="contributors.html">Contributors</a> | ' . $endln;
echo '        <a href="terms.html">Terms</a> | ' . $endln;
echo '	    <a href="https://lists.opengeospatial.org/mailman/listinfo/openpoidb-announce">Mailing List</a>' . $endln;
echo '    </div>' . $endln;
echo '		' . $endln;
echo '	<script>(function(d, s, id) {' . $endln;
echo '   displayPics('. $lat . ',' . $lon . '18);' . $endln;
echo '	  var js, fjs = d.getElementsByTagName(s)[0];' . $endln;
echo '	  if (d.getElementById(id)) return;' . $endln;
echo '	  js = d.createElement(s); js.id = id;' . $endln;
echo '	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=473919912642476";' . $endln;
echo '	  fjs.parentNode.insertBefore(js, fjs);' . $endln;
echo '	}(document, \'script\', \'facebook-jssdk\'));</script>' . $endln;
echo '</body>' . $endln;
echo '</html>' . $endln;

function getRepresentations($poi) {
  global $baseurl, $endln;
  $myid = $poi->getMyId();

  $thispoiurl = $baseurl . '/' . $myid;
  $htmldata = '<div id="poiitems" class="poiinfosection">' . $endln;
  $htmldata .= '<div id="source">' . $endln;
  $htmldata .= "<table>";
  
  $tdstyle = '<td style="text-align:right;padding-right:4px">';
  $htmldata .= "<tr>" . $tdstyle . "permalink:</td>";
  $htmldata .= "<td><a itemprop=\"url\" href=\"" . $thispoiurl . "\" target=\"_blank\">" . $myid . "</a></td></tr>\n";
  $htmldata .= "<tr>" . $tdstyle . "JSON:</td><td><a href=\"" . $thispoiurl . ".json\" target=\"_blank\">" . $myid . ".json</a></td></tr>\n";
  $htmldata .= "<tr>" . $tdstyle . "XML:</td><td><a href=\"" . $thispoiurl . ".xml\" target=\"_blank\">" . $myid . ".xml</a></td></tr>\n";
  $htmldata .= "<tr>" . $tdstyle . "HTML:</td><td><a href=\"" . $thispoiurl . "\" target=\"_blank\">" . $myid . ".html</a></td></tr>\n";
  $htmldata .= "</table>";
  $htmldata  .= '    <meta itemprop="map" content="http://' . $_SERVER['HTTP_HOST'] . '/map.html?id=' . $myid . '"/>' . $endln;

  // place Facebook Like button
  $htmldata .= '<div class="fb-like" data-href="' . $thispoiurl . '"';
  $htmldata .= ' data-send="false" data-width="450" data-show-faces="true"></div>';
  
  // license
  $license = $poi->getLicense();
  if ( !empty($license) ) {
    $htmldata .= "<div id=\"license\">\n";
    $htmldata .= "<p>license: ";
    $htmldata .= '<a href="' . $license->getHref() . ' title="' . $license->getValue() . '" target="_blank">' 
                    . $license->getTerm() . '</a>' . $endln;
    $htmldata .= '</div>' . $endln;
  }

  $htmldata .= '</div>' . $endln;
  $htmldata .= '</div>' . $endln;
  return $htmldata;
}

function getDescriptions($poi) {
  global $endln;
  $htmldata = '<div id="poidescriptions" class="poiinfosection">' . $endln;
  
  $descriptions = $poi->descriptions;
  if ( !empty($descriptions) ) {
    $htmldata .= '<div class="subhead">description</div>' . $endln;
    foreach ($descriptions as $d) {
      $htmldata .= '<p itemprop="description">' . $d->getValue() . '</p>' . $endln;
    }
  }
  $htmldata .= '</div>' . $endln;
  return $htmldata;
}

function getTags($poi) {
  global $endln;

  $htmldata = '<div id="tagsarea" class="poiinfosection">' . $endln;
  $htmldata .= '<p id="tags" class="subhead">tags</p>' . $endln;

  foreach ($poi->categories as $c) {
    $htmldata .= "<span class=\"tag\">";
    $htmldata .= "<span class=\"term\">";
    $s = $c->getScheme();
    if ( !empty($s) ) $htmldata .= '<a href="' . $s . '" target="_blank">';

    $htmldata .= $c->getTerm();
    if ( !empty($s) ) $htmldata .= "</a>";

    $htmldata .= "</span>"; // end term
    $htmldata .= "<span class=\"value\">";
    $v = $c->getValue();
    $i = $c->getId();
    if ( !empty($v) ) $htmldata .= $v;
    else if ( !empty($i) ) $htmldata .= $i;
    else $htmldata .= 'tag';
    $htmldata .= '</span>'; // end value
    $htmldata .= '</span>' . $endln; // end tag
  }
  
  
  $htmldata .= '</div>' . $endln;
  return $htmldata;
}

// LINKs (related place/resource) section
function getRelatedPlaces($poi) {
  include_once('poiconstants.php');
  global $endln;
  $hd = '<div id="placesarea" class="poiinfosection">' . $endln;
  $hd .= '<p id="places" class="subhead">related resources</p>' . $endln;
  $hd .= '<table id="relatedplaces" cellpadding="4px" width="100%">' . $endln;

  if ( !empty($poi->links) ) {
    foreach ($poi->links as $link) {
      $hd .= '<tr><td class="place">';

      $href = $link->getHref();
      if ( !empty($href) ) $hd .= '<a href="' . $href. '" target="_blank">';

      // $hd .= $link->getTerm() . ": ";
      // engage in a process of finding the best name for the link
      $relplacename = '';
      $v = $link->getValue();
      if ( !empty($v) ) {
        $relplacename = $v;
      } else {
        $v = $link->getBase();
        if ( !empty($v) ) {
          $relplacename = $v;
        } else {
          $v = $link->getId();
          if ( !empty($v) ) {
            $relplacename = $v;
          } else {
            $v = $link->getHref();
            if ( !empty($v) ) {
              $relplacename = $v;
            } else {
              $relplacename = "NONE ABLE TO BE FOUND";
            }
          }
        }
      }

      $relplacename = strtolower($relplacename);
      if ( strpos($relplacename, 'openstreetmap') !== FALSE ) $relplacename = 'openstreetmap';
      else if ( strpos($relplacename, 'geonames') !== FALSE ) $relplacename = 'geonames';
      else if ( strpos($relplacename, 'dbpedia') !== FALSE ) $relplacename = 'dbpedia';
      else if ( strpos($relplacename, 'futouring') !== FALSE ) $relplacename = 'futouring';
      else if ( strpos($relplacename, 'freebase') !== FALSE ) $relplacename = 'freebase';

      // add some context to the name
      $term = $link->getTerm();
      // if it's a related resource, show the related icon
      if ( $term == 'related') {
        $relplacename .= ' ' . $poilinks_related;
      } else {
        $relplacename .= ' ' . $term;
      }
      $hd .= $relplacename;
      
      // if this is an image, show a preview
      $imagetypes = array('image/jpeg', 'jpeg', 'image/png', 'png', 'image/gif', 'gif');
      $rtype = $link->getType();
      if ( !empty($rtype) && (array_search($rtype, $imagetypes) !== FALSE ) ) {
        $hd .= '<img itemprop="photo" src="' . $href . '" width="32">' . $endln;
      }

      if ( !empty($href) ) $hd .= "</a>";
      $hd .= "</td>"; // end related place name

      $hd .= "<td class=\"placeicons\">"; // begin icons showing what info the POI source provides
      $icondata = "&nbsp;";
      if ( strpos($relplacename, 'geonames') >= 0 ) $icondata = $poiatts_geonames;
      else if ( strpos($relplacename, 'openstreetmap') >= 0 ) $icondata = $poiatts_openstreetmap;
      else if ( strpos($relplacename, 'freebase') >= 0 ) $icondata = $poiatts_freebase;
      else if ( strpos($relplacename, 'dbpedia') >= 0 ) $icondata = $poiatts_dbpedia;
      else if ( strpos($relplacename, 'foursquare') >= 0 ) $icondata = $poiatts_foursquare;
      else if ( strpos($relplacename, 'twitter_place') >= 0 ) $icondata = $poiatts_twitter;
      else if ( strpos($relplacename, 'facebook') >= 0 ) $icondata = $poiatts_facebook;
      $hd .= $icondata;
      $hd .= "</td></tr>\n"; // end icons and related place    
    }
  }

  $hd .= "</table>\n"; // end table of related places
  $hd .= '</div>' . $endln;
  return $hd;
}

function getNearbyPlaces($myid, $lat, $lon) {
  global $endln;
  $hd = '<div id="nearbyplacesarea" class="poiinfosection">' . $endln;

  $pois[] = null;
  $poiids = findNearestPOIUUIDs($lat, $lon, 999, 6);
  
  $poinames = array();
  if ( !empty($poiids) ) {
    foreach ($poiids as $poiid) {
      $poinames[] = getPOIName($poiid);
    }
  }
  if ( empty($poinames) ) return '';

  $hd .= "<p class=\"subhead\">nearby places</p>";
  $hd .= "<ul>\n";
  for ($i=0; $i<sizeof($poiids); $i++) {
    $id = $poiids[$i];
    $v = empty($poinames[$i]) ? 'no name' : $poinames[$i];
    $hd .= "\t<li><a href=\"" . $id . ".html\">" . $v . "</a></li>\n";
  }
  $hd .= "</ul>\n";

  $hd .= '</div>' . $endln;
  return $hd;
}

?>
