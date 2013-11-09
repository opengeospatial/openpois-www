<?php
/**
 * Handles a couple different kinds of geographic queries:
 * 1: bounding box with an optional name
 *  params: bbox
 * 2: point radius search (no name allowed)
 *  params: lat, lon, radius (in meters)
 * in both cases, the following are optional parameters:
 *  name: name of the POI (label)
 *  maxfeatures: default is 25, range is 1 to 100
 *  format: default is application/json, other options are application/xml or xml or json
 * ex: http://localhost/openpoi/www/poiquery.php? 
 *        name=Kendall&bbox=42.362,-71.0880,42.372,-71.000&format=application/json
 * 
 * output = brief means return only the POIs' URIs
 */
include_once('class.poi.php');
include_once('utils.php');

$baseurl = 'http://' . $_SERVER['HTTP_HOST'] . '/pois';
parse_str($_SERVER['QUERY_STRING'], $q);
$query = array_change_key_case($q, CASE_LOWER);

$id = null;
$maxfeatures = 1;
$format = 'text/html';
$output = 'full';
$jsonp = false;
$callback = '';

if ( isset ($query['callback']) ) {
	$jsonp = true;
	$callback = $query['callback'];
}

if ( isset($query['id'])) {
  $ids = explode('.', $query['id']); // check if the id has a format extension
  $id = $ids[0];
  if ( sizeof($ids)>1 && isset($ids[1]) ) $format = $ids[1];
}

// set the output level
if (isset($query['output'])) {
  $o = trim( strtolower($query['output']) );
  if ( $o == 'ids') $output = $o;
  else if ( $o == 'brief') $output = $o;
  else $output = 'full';
}

// set the format of responses
if (isset($query['format'])) $format = trim( strtolower($query['format']) );
if ( $format == 'text/json' ) $format = 'application/json';
else if ( $format == 'json' ) $format = 'application/json';
else if ( $format == 'xml' ) $format = 'application/xml';
else if ( $format == 'text/xml' ) $format = 'application/xml';
else if ( $format == 'html' ) $format = 'text/html';
if ( $format == 'text/html' ) $maxfeatures = 1; // we can only handle 1 POI in an HTML response

// if we have an ID, ignore all other parameters
if ( empty($id) ) {
  $name = null;
  if (isset($query['name'])) $name = $query['name'];
  $bbox = null;
  if (isset($query['bbox'])) $bbox = $query['bbox'];
  $lat = null;
  if (isset($query['lat'])) $lat = $query['lat'];
  $lon = null;
  if (isset($query['lon'])) $lon = $query['lon'];
  $radius = 200;
  if (isset($query['radius'])) $radius = $query['radius'];
  $maxfeatures = 25;
  if (isset($query['maxfeatures']) && $query['maxfeatures'] < 101) $maxfeatures = $query['maxfeatures'];
  $srsname = 'urn:ogc:def:crs:EPSG::4326';
  
  $start = null;
  date_default_timezone_set('America/New_York');
  if (isset($query['start'])) {
    $start = phpDate($query['start']);
    if ($start == null) {
      sendError("Invalid start date: " . $query['start'], $_SERVER['QUERY_STRING'], $format);
    }
  }
  $end = null;
  if (isset($query['end'])) {
    $end = phpDate($query['end']);
    if ($end == null) {
      sendError("Invalid end date: " . $query['end'], $_SERVER['QUERY_STRING'], $format);
    }
  }
}

$querybycenter = true;
if ( empty($id) ) {
  if ( empty($lat) || empty($lon) || empty($radius) ) 
    $querybycenter = false;

  if ( empty($bbox) && !$querybycenter ) 
    sendError("Required parameter missing. Either bbox or lat, lon and radius required.", $_SERVER['QUERY_STRING'], $format);
  if ( $querybycenter ) {
    if ( $radius > 5000 || $radius < 1 ) 
      sendError("Radius value of $radius out of limits. Radius must be between 1 and 1,000.", $_SERVER['QUERY_STRING'], $format);
  }
}

$pois = null;

if ( !empty($id) ) { // first see if we can query by id
  // $px = POI::loadPOIUUID($id);
  $pois = array( POI::loadPOIUUID($id) );
  // if ( empty($px) ) {
  //   sendError("No POIS found for search: " . $_SERVER['QUERY_STRING'], $format);
  // }
  
} else if ( !empty($bbox) && !empty($name) ) { // then see if we can do a name + bbox
  $pois = queryByNameBBox($name, $bbox, $maxfeatures, $start, $end);
  
} else if ( !empty($bbox) ) { // then see if we can do a bbox only
  $pois = queryByBBox($bbox, $maxfeatures, $start, $end);

} else if ( $querybycenter ) { // if not, then do point/radius query
  $pois = queryByLatLonRad($name, $lat, $lon, $radius, $maxfeatures, $start, $end);
}

if ( empty($pois) && !empty($id) ) {
  sendError("No POIS found for ID: $id.", $_SERVER['QUERY_STRING'], $format);
} else if ( empty($pois) || (sizeof($pois)==1 && $pois[0] == null) ) {
  sendError("No POIS found for search: ", $_SERVER['QUERY_STRING'], $format);
}

// FOR DEBUGGING
// header("Content-Type: text/plain; charset=utf-8");
// foreach ($pois as $poi) {
//   echo $poi->asXML() . "\n";
// } 
// exit;
header("Expires: Mon, 22 Jul 2014 11:12:01 GMT");
if ( $format == 'application/xml') {
  header("Content-Type: application/xml; charset=utf-8");
  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
  
  if ( sizeof($pois) > 1 ) echo "<pois>\n";
  foreach ($pois as $poi)  echo $poi->asXML(false, false);
  // foreach ($pois as $poi) {
  //   echo "<poi id=\"" . $poi->getID() . "\" />";
  // } 
  if ( sizeof($pois) > 1 ) echo "</pois>\n";

} else if ( $format == 'application/json' ) {
  header("Content-Type: application/json; charset=utf-8");
	header("Access-Control-Allow-Origin: *");
	
	// handle JSONP request
	if ( $jsonp ) echo "$callback(";

  if ( sizeof($pois) == 1 && $output == 'full' ) {
    echo $pois[0]->asJSON();
  } else {
    echo "{\"pois\":[\n";
      if ( $output == 'ids') {
        echo "\t{\"id\":\"$baseurl/" . $pois[0]->getMyId() . "\" }";
        for ($i=1;$i<sizeof($pois);$i++) echo ",\n\t{\"id\":\"$baseurl/" . $pois[$i]->getMyId() . "\" }";
      } else if ( $output == 'brief' ) {
        echo "\t{\"name\":\"" . $pois[0]->labels[0]->getValue() . "\"";
        echo ",\"id\":\"$baseurl/" . $pois[0]->getMyId() . "\"";
        echo ",\"lat\":" . $pois[0]->getLocation()->getY();
        echo ",\"lon\":" . $pois[0]->getLocation()->getX() . "}";
        for ($i=1;$i<sizeof($pois);$i++) {
          echo ",\n\t{\"name\":\"" . $pois[$i]->labels[0]->getValue() . "\"";
            echo ",\"id\":\"$baseurl/" . $pois[$i]->getMyId() . "\"";
            echo ",\"lat\":" . $pois[$i]->getLocation()->getY();
            echo ",\"lon\":" . $pois[$i]->getLocation()->getX() . "}";
        }
      } else {
        echo $pois[0]->asJSON();
        for ($i=1;$i<sizeof($pois);$i++) echo ",\n\t" . $pois[$i]->asJSON();
      }
      echo "\n]}";
			// end handle JSONP request
			if ( $jsonp ) echo ");";
  }

} else if ( $format == 'text/plain' ) {
  header("Content-Type: text/plain; charset=utf-8");
  if ( sizeof($pois) > 1 ) echo "<pois>\n";
  foreach ($pois as $poi)  echo $poi->asXML();
  if ( sizeof($pois) > 1 ) echo "</pois>\n";

} else { // return HTML
  header("Content-Type: text/html; charset=utf-8");
  foreach ($pois as $poi) {//$poiid = $poi->id;
    include "poihtml.php";
  }
  // header("Location: /poihtml.php?id=$poiid");
}



function queryByLatLonRad($name, $lat, $lon, $radius) {
  global $maxfeatures, $start, $end, $output;
  $pois = array();
  $poiids = findNearestPOIUUIDs($lat, $lon, $radius, $maxfeatures);
  if ( !empty($poiids) ) {
    // if ( $output == 'ids' ) {
    //   return $poiids;
    // }
    
    foreach ($poiids as $poiid) {
      $pois[] = POI::loadPOIUUID($poiid);
    }
  } else {
    return null;
  }

  // do time search locally because I can't think of a good way to do it on the DB
  if ( !empty($start) || !empty($end) ) 
    $pois = queryByTime($pois, $start, $end);
  // do name search locally because database search is SLOOOOOW
  if ( !empty($name) ) 
    $pois = queryByName($pois, $name);

  return $pois;
}

function queryByNameBBox($name, $bbox, $maxfeatures, $start, $end) {
  global $output;
  // build points for bbox query
  // coords are XY: 0 left, 1 is lower, 2 is right, 3 is upper
  // so NO need to reverse to XY for PostGIS
  $cs = explode(',', $bbox);
  $leftlowpt = 'ST_Point(' . $cs[0] . ',' . $cs[1] . ')';
  $rightuppt = 'ST_Point(' . $cs[2] . ',' . $cs[3] . ')';
  $sql = "select poiuuid, label from minipoi";
  $sql .= " WHERE geompt && ST_SetSRID(ST_MakeBox2D($leftlowpt,$rightuppt),4326)";
  // if ( !empty($name) ) 
  //   $sql .= " AND label ILIKE '". addslashes($name) . "%'";
  // $sql .= " LIMIT $maxfeatures";
  // echo ("running query: $sql\n");
  
  $pois = array();
  $conn = getDBConnection();
  $c = $conn->query($sql);      
  if ( $c ) {
    foreach($c as $row) {
      // do name search locally because database search is SLOOOOOW
      if ( !empty($name) && ( stripos( $row['label'], $name) === false ) ) continue;
      if ( $output == 'ids' ) {
        $pois[] = $row['poiuuid'];
      } else {
        $pois[] = POI::LoadPOIUUID($row['poiuuid']);
      }
    }
  }
  
  // limit to maxfeatures
  $toobig = true;
  while ($toobig) {
    if ( sizeof($pois) > $maxfeatures ) {
      array_pop(&$pois);
    } else {
      $toobig = false;
    }
  }

  if ( $output != $brief ) {
    $pois = queryByTime($pois, $start, $end);
  }
  return $pois;
}

function queryByBBox($bbox, $maxfeatures, $start, $end) {
  global $output;
  // build points for bbox query
  // coords are XY: 0 left, 1 is lower, 2 is right, 3 is upper
  // so NO need to reverse to XY for PostGIS
  $cs = explode(',', $bbox);
  $leftlowpt = 'ST_Point(' . $cs[0] . ',' . $cs[1] . ')';
  $rightuppt = 'ST_Point(' . $cs[2] . ',' . $cs[3] . ')';
  $sql = "select poiuuid, label from minipoi";
  $sql .= " WHERE geompt && ST_SetSRID(ST_MakeBox2D($leftlowpt,$rightuppt),4326)";
  // if ( !empty($name) ) 
  //   $sql .= " AND label ILIKE '". addslashes($name) . "%'";
  if ( !empty($maxfeatures) ) 
    $sql .= " LIMIT $maxfeatures";
  // echo ("running query: $sql\n");
  
  $pois = array();
  $conn = getDBConnection();
  $c = $conn->query($sql);      
  if ( $c ) {
    foreach($c as $row) {
      if ( $output == 'ids' ) {
        $pois[] = $row['poiuuid'];
      } else {
        $pois[] = POI::LoadPOIUUID($row['poiuuid']);
      }
    }
  }

  if ( $output != 'ids' ) {
    $pois = queryByTime($pois, $start, $end);
  }
  return $pois;
}

// ex: http://localhost/openpoi/www/poiquery.php?lat=25.959&lon=119.519&maxfeatures=1&format=xml&radius=1000&start=944
function queryByTime($pois, $start, $end) {
  if ( empty($start) && empty($end) ) return $pois;
  
  $goodpois = array();
  foreach ( $pois as $poi) {
    if ( empty($poi->times) ) {
      $goodpois[] = $poi;
      continue;
    }
    
    // check the POI for start and end times.
    $times = $poi->times;
    foreach ( $times as $time) {
      $passedtime = 0; // if time passes both start and end query requirements, value will be 2
      // echo ("here with name: " . $poi->labels[0]->getValue() . " and time term: '" . $time->term . "' and value: " . $time->getValue()."<br/>\n");
      $term = $time->term;
      if ( stripos($term, "start") !== false || stripos($term, "end") !== false ) {        
        // FINALLY check start time
        if ( stripos($term, "start") !== false && !empty($start) ) {
          $poistart = phpDate( $time->getValue() );
          if ( $poistart == null ) {
            $passedtime++;
          } else {
            if ( $poistart < $start) continue;
            else $passedtime++;
          }
        } else {
          $passedtime++;
        }
        
        // FINALLY check end time
        if ( stripos($term, "end") !== false && !empty($end) ) {
          $poiend = phpDate( $time->getValue() );
          if ( $poiend == null ) { // pass it if we can't understand it
            $passedtime++;
          } else {
            if ( $poiend > $end) continue;
            else $passedtime++;
          }
        } else {
          $passedtime++;
        }

        if ( $passedtime >= 2 ) 
          $goodpois[] = $poi;
      }
    }
  }
  return $goodpois;
}

function queryByName($pois, $name) {
  $goodpois = array();
  foreach ( $pois as $poi) {
    $labels = $poi->labels;
    foreach ( $labels as $label) {
      // echo ("here with name: $name and label: " . $label->getValue()."\n");
      if ( stripos( $label->getValue(), $name) !== false ) {
        $goodpois[] = $poi;
      }
    }
  }
  return $goodpois;
}

function sendError($msg, $query='', $format='text/plain', $status='400 Bad Request') {
  if ( $format == 'application/xml' ) {
    header("Content-Type: application/xml; charset=utf-8");
    header("HTTP/1.0 $status");
    echo "<Error>\n\t<msg>$msg</msg>\n\t<query><![CDATA[$query]]></query></Error>\n";
    
  } else if ( $format == 'application/json' ) {
    header("Content-Type: application/json; charset=utf-8");
    header("HTTP/1.0 $status");
    echo '{"err": {"message": "' . $msg . '", "query": "' . $query . '"}}';
    
  } else {
    header("Content-Type: text/plain; charset=utf-8");
    header("HTTP/1.0 $status");
    echo "message: $msg\nquery: $query";
  }
  die;
}

?>
