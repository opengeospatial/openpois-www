<?php 
require_once "common.php";
include('../header.php');
?>

	<link rel="stylesheet" href="../css/login.css" type="text/css">
	<title>OpenPOIs Registry OpenID Login</title>
</head>

<body>
	<div id="banner">
		<span id="title">OpenPOIs</span>
		<span id="sub">the web of location data</span>
	</div>
	<div id="data" class="ink-container">

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
    <form class="ink-form block" method="GET" action="try_auth.php">
		<label for="openid_identifier">Identity URL</label>
      <input type="text" name="openid_identifier" value="" />
      <input class="ink-button info" type="submit" value="Verify" />
      <input type="hidden" name="action" value="verify" />
    </form>
  </div>
<?php } ?>
</div><!-- end div id=data-->

<?php include('../footer.php'); ?>
</body>
</html>