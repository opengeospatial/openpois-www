<!DOCTYPE html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>OpenPOI Atlas Login page</title>
	<link type="text/css" rel="stylesheet" href="css/MyFontsWebfontsKit.css">
	<link rel="stylesheet" href="css/poi.css" type="text/css">
	<link rel="stylesheet" href="css/login.css" type="text/css">
	<script src="js/login.js"></script>
</head>
<body>
	<?php
		if ( isset( $_COOKIE['uid'] ) ) {
			$uid = $_COOKIE['uid'];
			if ( isset ($_SESSION['loginreferrer']) ) {
				$lf = $_SESSION['loginreferrer'];
				header('Location:' . $lf);
			} else {
				header('Location:/');
			}
		}
	?>
	<div id="banner">
		<span id="title">OpenPOIs Atlas</span>
		<span id="sub">the hub of location data on the web</span>
	</div>
	<div id="data">
		<p class="headline">Sign in with any of these sites</p>
	<div id="fb-root"></div>
	<script>
	  // Additional JS functions here
	  window.fbAsyncInit = function() {
	    FB.init({
	      appId      : '473919912642476', // App ID
	      channelUrl : '//openpois.ogcnetwork.net/channel.html', // Channel File
	      status     : true, // check login status
	      cookie     : true, // enable cookies to allow the server to access the session
	      xfbml      : true  // parse XFBML
	    });

	    // Additional init code here
		FB.getLoginStatus(function(response) {
		  if (response.status === 'connected') {
		    // connected
		  } else if (response.status === 'not_authorized') {
		    // not_authorized
		  } else {
		    // not_logged_in
			FBlogin();
		  }
		 });
		
		
	  };

	  // Load the SDK Asynchronously
	  (function(d){
	     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement('script'); js.id = id; js.async = true;
	     js.src = "//connect.facebook.net/en_US/all.js";
	     ref.parentNode.insertBefore(js, ref);
	   }(document));
	</script>
	
	<div>
		<a href="#" onclick="FBlogin()">
			<img src="graphics/social_signin/facebook_signin.png" alt="Sign in with Facebook">
		</a>
	<div>
		<div>
			<a href="#" onclick="#">
				<img src="graphics/social_signin/openid_signin.png" alt="Sign in with OpenID">
			</a>
		<div>
</div>
<p><!-- end bars --></p>
<div id="komodocredit"><p><a href="http://www.komodomedia.com/blog/2009/06/sign-in-with-twitter-facebook-and-openid/">sign in graphics courtesy of komodomedia.com</a></p></div>
<div id="footer">
	<a href="/">Home</a> | <a href="api.html">API</a> | 
    <a href="faq.html">FAQ</a> | 
    <a href="contributors.html">Contributors</a> | 
    <a href="terms.html">Terms</a> | 
    <a href="https://lists.opengeospatial.org/mailman/listinfo/openpoidb-announce">Mailing List</a>
</div>
</body>
</html>