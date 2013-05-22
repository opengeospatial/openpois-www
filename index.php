<?php include('header.php'); ?>
	<link rel="stylesheet" href="css/main.css" type="text/css">
	<title>OpenPOIs Registry</title>
</head>
<body>
		<script type="text/javascript">
(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=473919912642476";
		fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		</script>
	<div id="masthead">
		<img src="/graphics/logo_banner_blueblue.png" style="width:400px">
		<div id="subx">serving data on 9 million places, and counting...</div>
	</div><!--<p><b>What can I do with the OpenPOI Registry?</b></p>-->

	<div class="ink-container">
		<div id="motto">open, collaborative, standardized points of interest for the world</div>
		<div id="content">
			<ul>
				<li>Get data <span class="dimmed">using the </span><a href="api.php">API</a></li>
				<li><span class="dimmed">Explore the </span><a href="map.html">map</a></li>
				<li>Contribute<span class="dimmed"> to the cause. Add your own tags and descriptions on any POI listing</span></li>
				<li><a href="b2b/">Manage your business listing</a><span class="dimmed"> in one place. Let Google, Yelp, Bing, Facebook, etc. </span>update from a single source</li>
			</ul>
		</div>

		<p>&nbsp;</p>
		<div id="fb-root"></div>
    <div data-show-faces="true" data-width="450" data-send="true" data-href="http://openpois.net"
      class="fb-like"></div>
		<p>&nbsp;</p>
		<?php include('footer.php'); ?>
	</div><!-- end ink-container -->
	</body>
</html>
