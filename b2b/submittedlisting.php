<?php
include_once('class.poi.php');
include_once('utils.php');

$data = '';

date_default_timezone_set("UTC");
$date = date('Ymd-his', time());
$data .= "timestamp: $date\n";
$formatteddata = '';

$poi = new POI( gen_uuid() );
if ( isset($_POST['youremail'])) {
	$v = $_POST['youremail'];
	$pa = new POITermType('AUTHOR', 'owner');
	$pa->setId($v);
	$poi->setAuthor($pa);
}

if ( isset($_POST['businessname'])) {
	$v = $_POST['businessname'];
	$pl = new POITermType('LABEL', 'primary');
	$pl->setValue($v);
	$poi->addLabel($pl);
}

if ( isset($_POST['businessweb'])) {
	$pd = new POITermType('LINK', 'related', NULL, $iana);
	$pd->setHref($_POST['businessweb']);
	$poi->addDescription($pd);
}

if ( isset($_POST['businessdescription'])) {
	$pd = new POIBaseType('DESCRIPTION');
	$pd->setValue($_POST['businessdescription']);
	$poi->addDescription($pd);
}

if ( isset($_POST['businesstype'])) {
	$c = $_POST['businesstype'];
	$pc = new POITermType('CATEGORY', $c);
	$pc->setValue($c);
	$poi->addCategory($pc);
}

foreach ($_POST as $key => $val) {
	$data .= "$key: $val\n";
	$formatteddata .= "<p>$key: $val</p>\r\n";
}

$fn = getcwd() . "/submissions/" . $date . ".txt";
$ok = file_put_contents( $fn, $data );
if ( $ok === FALSE ) {
	$data .= "COULDN'T SUBMIT THE LISTING!!\r\n";
	$formatteddata .= "COULDN'T SUBMIT THE LISTING!!\r\n";
}

//// email it
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
// $headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";

// Additional headers
$headers .= "To: <rsingh@opengeospatial.org>\r\n";
$headers .= "From: OpenPOIs Admin <openpois@opengeospatial.org>\r\n";

// Mail it
$emaildata = $formatteddata . "\r\n" . $poi->asXML();
mail('rsingh@opengeospatial.org', 'B2B Submission', $emaildata, $headers);

?>

<!DOCTYPE html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>OpenPOIs Repository Business Listing Submission</title>
	<link type="text/css" rel="stylesheet" href="../css/MyFontsWebfontsKit.css">
	<link rel="stylesheet" href="../css/poi.css" type="text/css">
	<link rel="stylesheet" href="../css/ink/css/ink.css">
	<!--[if IE]>
	<link rel="stylesheet" href="css/ink/css/ink-ie.css" type="text/css" media="screen" title="none" charset="utf-8">
  	<![endif]-->
</head>
<body>
	<div class="ink-container">
	<h3>Thank you for your submission!</h3>
	<?php echo $formatteddata; ?>
<?php include ('../footer.php'); ?>
</div>
</body>
</html>
