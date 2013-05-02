<?php 
require_once "common.php";
?>

<!DOCTYPE html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>OpenPOIs Registry OpenID Login</title>
	<link type="text/css" rel="stylesheet" href="../css/MyFontsWebfontsKit.css">
	<link rel="stylesheet" href="../css/poi.css" type="text/css">
	<link rel="stylesheet" href="../css/login.css" type="text/css">
	<!-- <script src="../js/login.js"></script> -->
</head>

<body>
	<div id="banner">
		<span id="title">OpenPOIs Atlas</span>
		<span id="sub">the hub of location data on the web</span>
	</div>
	<div id="data">

<?php
if (isset($msg)) {
  print "<div class=\"alert\">$msg</div>"; 
}
if (isset($error)) { 
  print "<div class=\"error\">$error</div>"; 
}
if (isset($success)) { 
  print "<div class=\"success\">$success</div>";
} else { 
?>
  <p style="text-align:center"><img src="../graphics/openid-logo-sm.png"></p>
  <div id="verify-form">
    <form method="get" action="try_auth.php">
      Identity&nbsp;URL:
      <input type="text" name="openid_identifier" value="" />
      <input type="submit" value="Verify" />
      <input type="hidden" name="action" value="verify" />
    </form>
  </div>
<?php } ?>
</div><!-- end div id=data-->

<p><!-- end bars --></p>
<div id="footer">
	<a href="/">Home</a> | <a href="api.html">API</a> | 
    <a href="faq.html">FAQ</a> | 
    <a href="contributors.html">Contributors</a> | 
    <a href="terms.html">Terms</a> | 
    <a href="https://lists.opengeospatial.org/mailman/listinfo/openpoidb-announce">Mailing List</a>
</div>
</body>
</html>