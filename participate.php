<?php include('header.php'); ?>
    <title>OpenPOIs Participation</title>
  </head>
  <body>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=473919912642476";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
    
    <div id="banner">
    	<span id="title"></span>
    	<span id="sub">the web of location data</span>
			<?php include('footer.php'); ?>
    </div>
	<div class="ink-container">
		<h1>Participate</h1>
		
		<div>
			There are many ways to be a part of the development of the world's largest open POI database and the emerging standard that makes it possible:
			<h2>Join the discussion</h2>

			<h3>about the candidate OGC Points of Interest (POI) Encoding Standard...</h3>
			<p>Join the mailing list devoted to the development of the candidate OGC POI Encoding Standard, which is governed by an official OGC standards working group (the POI SWG). All individuals who want to be on the POI SWG mailing list will need to sign the same Observer Agreement that members of the OGC OpenPOI Standards Working Group must sign. This OGC Standards Working Group policy is necessary in order to ensure that all OGC standards remain Reasonable And Non-Discriminatory and Royalty-Free (RAND-RF) for the good of everyone. Download the agreement here [LINK], then sign it and return it via fax, mail or emailed scanned image. Include your email address. We will then email you your initial POI SWG username and password.</p>

			<h3>about the OpenPOIs database and the web services that make it accessible...</h3>
			<p>OGC OpenPOIs, the world's largest open POI database, is an OGC prototype that implements the candidate OGC POI Encoding Standard. There are two mailing lists related to OpenPOIs development. <a href="https://lists.opengeospatial.org/mailman/listinfo/OpenPOIs-users">OpenPOIs-users@lists.opengeospatial.org</a> is a group of people interested in improving the data and/or code in the system, using the open source code to develop their own POI systems, or connecting POI systems together. OpenPOIs source code is available on GitHub in a <a href="http://github.com/rajrsingh/openpois/">repository with back-end code to do database management and POI conflation</a>, and in a <a href="http://github.com/rajrsingh/openpois-www/">repository for the web site, web services, and mapping</a>.
				
				<p><a href="https://lists.opengeospatial.org/mailman/listinfo/OpenPOIdb-announce">OpenPOIdb-announce@lists.opengeospatial.org</a> is for those who just want to receive announcements about major events in the evolution of OpenPOIs.</p>

			<h2>Edit POI listings by hand</h2>
			At openpois.net, add your own tags and descriptions on any POI listing by signing in on any POI page (click the "Sign in" button in the top right corner). After signing in you should notice that you now see pencil icons above the descriptions and tags. Click on the pencil (<i class="icon-pencil add-poi-info"></i>) and add tags or descriptive paragraphs.
			</div>

	    <p><br />
	    </p>

    <div data-show-faces="true" data-width="450" data-send="true" data-href="http://openpois.net/api.html"
      class="fb-like"></div>
	    <p><br />
	    </p>

		</div><!-- end ink-container -->
  </body>
</html>
