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
$format = 'text/xml';
$output = 'summary';
$outputschema = null;

//// make sure service and version are CSW and 3.0.0
// checkBasicParams();

//// make sure there's an ID parameter set
if ( !isset($query['id'])) {
  // return error
}
$id = $query['id'];

// set the output level
if (isset($query['output'])) {
  $o = trim( strtolower($query['output']) );
  if ( $o == 'full') $output = $o;
  else if ( $o == 'brief') $output = $o;
  else $output = 'summary';
}

// set the output schema
if (isset($query['outputschema'])) {
  $o = trim( strtolower($query['outputschema']) );
  if ( $o == 'http://www.opengis.net/cat/csw/3.0') $output = $o;
}

// set the format of responses
if (isset($query['outputformat'])) $format = trim( strtolower($query['outputformat']) );
if ( $format == 'text/json' ) $format = 'application/json';
else if ( $format == 'json' ) $format = 'application/json';
else if ( $format == 'xml' ) $format = 'application/xml';
else if ( $format == 'text/xml' ) $format = 'application/xml';
else if ( $format == 'html' ) $format = 'text/html';
if ( $format == 'text/html' ) $maxfeatures = 1; // we can only handle 1 POI in an HTML response



$pois = array( POI::loadPOIUUID($id) );

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
if ( $format == 'application/xml') {
  header("Content-Type: application/xml; charset=utf-8");
  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
  
  if ( empty($outputschema) || $outputschema == 'http://www.opengis.net/cat/csw/3.0') {
    echo getCSWCommon($pois[0], $output);
    
  } else {
    echo $pois[0]->asXML(false, false);
  }


} else if ( $format == 'application/json' ) {
  header("Content-Type: application/json; charset=utf-8");
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
  }

} else if ( $format == 'text/plain' ) {
  header("Content-Type: text/plain; charset=utf-8");
  echo $pois[0]->asXML();

} else { // return HTML
  header("Content-Type: text/html; charset=utf-8");
  foreach ($pois as $poi) {//$poiid = $poi->id;
    include "poihtml.php";
  }
}


function getCSWCommon($poi, $output) {
  $o = 'xmlns:csw30="http://www.opengis.net/cat/csw/3.0"'
    . ' xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dct="http://purl.org/dc/terms/"' 
    . ' xmlns:ows="http://www.opengis.net/ows/2.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' 
    . ' xsi:schemaLocation="http://www.opengis.net/cat/csw/3.0 ../../../csw/3.0/record.xsd">';
    
  if ( $output == 'brief' ) {
    $o = '<csw30:BriefRecord ' . $o;
  } else if ( $output == 'summary' ) {
    $o = '<csw30:SummaryRecord ' . $o;
  } else if ( $output == 'full' ) {
    $o = '<csw30:Record ' . $o;
  }
    
  $o .= "<dc:identifier>" . $poi->getMyId() . "</dc:identifier>\n";
  $o .= "<dc:title>" . $poi->getFirstLabelName() . "</dc:title>\n";
  $o .= "<dc:type>landmarks</dc:type>\n";
  $o .= "<dc:type>pois</dc:type>\n";
  $o .= "<dc:type>pointsofinterest</dc:type>\n";
  
  if ( $output != 'brief') {
    if ( $output == 'full' ) {
      $o .= "<dc:creator>Open Geospatial Consortium</dc:creator>\n";
    }
    // $cats = $poi->categories;
    // foreach ($cats as $cat) {
    //   $o .= "<dc:subject>" . $cat->getTerm() . "</dc:subject>\n";
    // }
    $o .= "<dc:modified>" . $poi->getUpdated() . "</dc:modified>\n";
    if ( !empty($poi->descriptions[0]) ) {
      $d = $poi->descriptions[0];
      $o .= "<dc:abstract>" . $d->getValue() . "</dc:abstract>\n";
    }
    
    $l = $poi->getLocation();
    $lon = $l->getX();
    $lat = $l->getY();
    $o .= "<ows:BoundingBox>\n";
    $o .= "\t<ows:LowerCorner>$lon,$lat</ows:LowerCorner>\n";
    $o .= "\t<ows:UpperCorner>$lon,$lat</ows:UpperCorner>\n";
    $o .= "</ows:BoundingBox>\n";
    
    //// @TODO TemporalExtent
  }
  
  if ( $output == 'brief' ) {
    $o .= "</csw30:BriefRecord>\n";
  } else if ( $output == 'summary' ) {
    $o .= "</csw30:SummaryRecord>\n";
  } else if ( $output == 'full' ) {
    $o .= "</csw30:Record>\n";
  }
  
  return $o;
}


function sendError($msg, $query='', $format='text/plain') {
  if ( $format == 'application/xml' ) {
    header("Content-Type: application/xml; charset=utf-8");
    header("HTTP/1.0 500 Internal Server Error");
    echo "<Error>\n\t<msg>$msg</msg>\n\t<query><![CDATA[$query]]></query></Error>\n";
    
  } else if ( $format == 'application/json' ) {
    header("Content-Type: application/json; charset=utf-8");
    header("HTTP/1.0 500 Internal Server Error");
    echo '{"err": {"message": "' . $msg . '", "query": "' . $query . '"}}';
    
  } else {
    header("Content-Type: text/plain; charset=utf-8");
    header("HTTP/1.0 500 Internal Server Error");
    echo "message: $msg\nquery: $query";
  }
  die;
}

?>
