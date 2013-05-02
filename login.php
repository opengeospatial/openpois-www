<?php
session_start();
if ( isset( $_COOKIE['uid'] ) ) {
	if ( isset ($_SESSION['loginreferrer']) ) {
		$lf = $_SESSION['loginreferrer'];
		header('Location:' . $lf);
	} else {
		header('Location:/');
	}
}

include('header.php');
?>
	<link rel="stylesheet" href="css/login.css" type="text/css">
	<title>OpenPOIs Registry Login</title>
</head>
<body>
	<div id="banner">
		<span id="title">OpenPOIs</span>
		<span id="sub">the hub of location data on the web</span>
	</div>
	<div class="ink-container">
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
				<img src="graphics/social_signin/facebook_signin.png" alt="Sign in with Facebook"/>
			</a>
		<div>
		<div>
			<a href="login_openid.php">
				<img src="graphics/social_signin/openid_signin.png" alt="Sign in with OpenID"/>
			</a>
		<div>
		<div>
			<ul>
				<li>One less password to remember</li>
				<li>We won't automatically post to your wall</li>
			</ul>
		</div>
	</div><!-- end ink-container -->
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