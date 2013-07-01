<?php
/**
 * poiupdate.php
 * Takes a request to update a POI with a single category, label, description, or link
 * parameters: 
 * id: POI UUID, <all the required params of the object type
 * ex: http://poi.local/poiupdate.php? 
 *        id=Kendall&bbox=42.362,-71.0880,42.372,-71.000&format=application/json
 */
include_once('class.poi.php');
include_once('utils.php');
session_start();

$baseurl = 'http://' . $_SERVER['HTTP_HOST'] . '/pois';
$rawquery = $_SERVER['QUERY_STRING'];
parse_str($rawquery, $q);
$query = array_change_key_case($q, CASE_LOWER);

$id = $type = $term = $value = $scheme = $href = $lang = $base = NULL;

if ( !isset($query['id']) ) sendError('id is required', $rawquery);
else $id = $query['id'];
if ( !isset($query['type']) ) sendError('type is required', $rawquery);
else $type = $query['type'];

$id = $query['id'];
$poi = POI::loadPOIUUID($id);
$uid = $_SESSION['uid'];
$author = new POIBaseType('AUTHOR');
$author->setId($uid);

// initialize all variables
if ( isset($query['type']) ) $type = $query['type'];
if ( isset($query['lang']) ) $lang = $query['lang'];
if ( isset($query['base']) ) $base = $query['base'];
if ( isset($query['term']) ) $term = $query['term'];
if ( isset($query['value']) ) $value = $query['value'];
if ( isset($query['scheme']) ) $scheme = $query['scheme'];
if ( isset($query['href']) ) $href = $query['href'];

// type of object
switch ($type) {
	case 'CATEGORY': 
		if ( empty($term) ) sendError('term is required', $rawquery);
		$poiobj = new POITermType('CATEGORY', $term, $value, $scheme);
		$poiobj->setAuthor($author);
		$ok = $poi->updatePOIProperty($poiobj, TRUE);
		if ( $ok ) {
			// return success
			sendSuccess($poiobj);
		} else {
			sendError('error adding category to POI '.$id, $rawquery);
		}
		break;
		
	case 'DESCRIPTION': 
		if ( empty($value) ) sendError('description is required', $rawquery);
		$poiobj = new POIBaseType('DESCRIPTION');
		$poiobj->setValue($value);
		$poiobj->setAuthor($author);
		$ok = $poi->updatePOIProperty($poiobj, TRUE);
		break;
		
	default: 
		sendError('unknown type', 'type='.$type);
		break;
}


// FOR DEBUGGING
// header("Content-Type: text/plain; charset=utf-8");
// foreach ($pois as $poi) {
//   echo $poi->asXML() . "\n";
// } 
// exit;

/**
 * Return the object added as JSON
 */
function sendSuccess($obj) {
	header("Content-Type: application/json; charset=utf-8");
	echo json_encode($obj);
  die;
}

function sendError($msg, $query=NULL) {
	header("Content-Type: application/json; charset=utf-8");
	header("HTTP/1.0 400 Bad Request");
	echo '{"err": {"message": "' . $msg . '"';
	if ( !empty($query) ) echo ', "query": "' . htmlspecialchars($query) . '"';
	echo '}}';
  die;
}

?>
