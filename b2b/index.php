<?php include('../header.php'); ?>
	<script type="text/javascript" src="http://js.sapo.pt/Bundles/Ink-v1.js"></script>
	<title>OpenPOIs Repository Business Listing Submission</title>
</head>
<body>
	<div id="banner">
		<?php include_once('../loginout.php'); echo ( getButton('http://' . $_SERVER['HTTP_HOST'] . '/b2b/') );?>
		<span id="title"></span>
		<span id="sub">the web of location data</span>
	</div>
	<div class="ink-container">
		<h3><br>Submit/Update your business listing</h3>
		<div id="explanation">
			<p>Enter the details of your business here and click Submit. Your information will be reviewed and added to OpenPOIs within 24 hours. If this is an update to an existing business, don't worry. We'll figure that out and handle it appropriately.</p>
		</div>
		<hr>
		<div id="listingform" class="ink-l100 ink-m100 ink-s100">
			<p>(starred fields are required)</p>
			<form class="ink-form block" method="POST" action="submittedlisting.php">
				<fieldset>
			  <div class="control required">
			    <label for="yourname">Your Name</label>
			    <input type="text" name="yourname" value="" />
			  </div>
			  <div class="control required">
			    <label for="youremail">Your Email</label>
			    <input type="text" name="youremail" value="" 
                            placeholder="used only in case of questions" />
			  </div>
			<hr>
			  <div class="control required">
			    <label for="businessname">Business Name</label>
			    <input type="text" name="businessname" value="" />
			  </div>
			  <div class="control">
			    <label for="businessweb">Business Home Page</label>
			    <input type="text" name="businessweb" value="" placeholder="http://www.example.com" />
			  </div>
			  <div class="control">
			    <label for="businessdescription">Business Description</label>
			    <input type="text" name="businessdescription" value="" placeholder="Say a little more than what's in the title"/>
			  </div>
			  <div class="control">
			    <label for="businesstype">Business Type</label>
			    <input type="text" name="businesstype" value="" 
                            placeholder="such as restaurant, law office, toy store, etc."/>
			  </div>
			<hr>
			  <div class="control required">
			    <label for="addressline1">Address Line 1</label>
			    <input type="text" name="addressline1" value="" />
			  </div>
			  <div class="control">
			    <label for="addressline2">Address Line 2</label>
			    <input type="text" name="addressline2" value="" />
			  </div>
			  <div class="control required">
			    <label for="city">City</label>
			    <input type="text" name="city" value="" />
			  </div>
			  <div class="control required">
			    <label for="state">State/Province/Region</label>
			    <input type="text" name="state" value="" />
			  </div>
			  <div class="control required">
			    <label for="zip">ZIP/Postal Code</label>
			    <input type="text" name="zip" value="" />
			  </div>
			  <div class="control required">
			    <label for="country">Country</label>
			    <input type="text" name="country" value="" />
			  </div>
			  <div class="control">
			    <input class="ink-button info" type="submit" value="Submit" />
			  </div>
			</fieldset></form>
		</div><!-- end listing form-->
	</div><!-- end container-->
	<p><!-- end bars --></p>
	<?php include('../footer.php'); ?>
</body>
</html>
