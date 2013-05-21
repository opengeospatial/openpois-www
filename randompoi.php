<?php
include_once('utils.php');

parse_str($_SERVER['QUERY_STRING'], $q);
$query = array_change_key_case($q, CASE_LOWER);

$lat = null;
if (isset($query['lat'])) $lat = $query['lat'];
else sendError('missing lat', $query);

$lon = null;
if (isset($query['lon'])) $lon = $query['lon'];
else sendError('missing lon', $query);

$poiids = findNearestPOIUUIDs($lat, $lon, 999, 10);
if ( empty($poiids) || count($poiids) < 1 ) 
	sendError('No nearby POIs', $query, '500 Internal Server Error');

$choice = rand(0, count($poiids));
$poiid = $poiids[$choice];
header('Location:/pois/'.$poiid);  

function sendError($msg, $query, $status='400 Bad Request') {
    header("Content-Type: application/json; charset=utf-8");
    header("HTTP/1.0 $status");
    echo '{"err": {"message": "' . $msg . '", "query": "' . $query . '"}}';
  die;
}

?>