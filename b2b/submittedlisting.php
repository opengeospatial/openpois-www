<?php
$data = '';

date_default_timezone_set("UTC");
$date = date('Ymd-his', time());
$data .= "timestamp: $date\n";
$formatteddata = '';

foreach ($_POST as $key => $val) {
	$data .= "$key: $val\n";
	$formatteddata .= "<p>$key: $val</p>\n";
}

$fn = getcwd() . "/submissions/" . $date . ".txt";
file_put_contents( $fn, $data );


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
