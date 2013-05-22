<?php include('header.php'); ?>
    <title>OpenPOI Registry Web Services API</title>
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
    </div>
	<div class="ink-container">
		<h1>Web Services API</h1>

    <h3>Request</h3>
    <span class="codehl">http://openpois.net/poiquery.php?</span>
    <h3>Parameters</h3>
    <table class="ink-table ink-zebra ink-hover" width="80%">
      <tbody>
        <tr>
          <th width="96" scope="col">parameter</th>
          <th width="96" scope="col">type</th>
          <th scope="col">description</th>
        </tr>
        <tr>
          <td>id</td>
          <td><em>UUID</em></td>
          <td>globally unique id of the POI<br />
            <em>if present, all other parameters (except format) are ignored</em></td>
        </tr>
        <tr>
          <td>format</td>
          <td><em>string<br />
            </em></td>
          <td>MIME type of the return value<br />
              <strong>options:</strong> text/html, application/json or
              application/xml <br />
              <strong>default:</strong> text/html
          </td>
        </tr>
        <tr>
          <td>name</td>
          <td><em>string</em></td>
          <td>primary name/label of the POI<br />
            <em>required if id is not present</em></td>
        </tr>
        <tr>
          <td>bbox</td>
          <td><em>comma-separated decimals</em></td>
          <td>geographic bounding box in EPSG:4326 (lat/lon)<br />
            <strong>format:</strong> left,lower,right,upper<br />
            <em>if present, lat/lon/radius are ignored</em></td>
        </tr>
        <tr>
          <td>lat</td>
          <td><em>decimal</em></td>
          <td>latitude<br />
            <em>required for lat/lon/radius search</em></td>
        </tr>
        <tr>
          <td>lon</td>
          <td><em>decimal</em></td>
          <td>longitude<br />
            <em>required for lat/lon/radius search</em></td>
        </tr>
        <tr>
          <td>radius</td>
          <td><em>decimal</em></td>
          <td>search distance from lat/lon in meters<br />
              <em>used only for lat/lon/radius search<br />
              </em><strong>default:</strong> 50
          </td>
        </tr>
        <tr>
          <td>start</td>
          <td><em>date</em></td>
          <td>POI came into existence after this date (POIs with no start date
            are included)<br />
            <strong>ex:</strong> 2011-10-01</td>
        </tr>
        <tr>
          <td>end</td>
          <td><em>date</em></td>
          <td>POI ended existence before this date (POIs with no end date are
            included)<br />
            <strong>ex:</strong> 2012-05-01</td>
        </tr>
        <tr>
          <td>maxfeatures</td>
          <td><em>integer</em></td>
          <td>maximum number of POIs to return<br />
            <strong>default:</strong> 25 (maximum allowed is 25 also)</td>
        </tr>
      </tbody>
    </table>
    <h3>Query Examples</h3>
    <h4>ID</h4>
    <p class="codehl"><a href="http://openpois.net/poiquery.php?id=adc77fe6-5ad3-4d83-b109-5cb44eb62267&amp;format=application/json"
        target="_new">http://openpois.net/poiquery.php?id=adc77fe6-5ad3-4d83-b109-5cb44eb62267&amp;format=application/json
        </a></p>
    <h4>RESTful ID</h4>
    <p class="codehl"><a href="http://openpois.net/pois/adc77fe6-5ad3-4d83-b109-5cb44eb62267.xml"
        target="_new">http://openpois.net/pois/adc77fe6-5ad3-4d83-b109-5cb44eb62267.xml</a></p>
    <h4>Radius and point</h4>
    <p class="codehl"><a href="http://openpois.net/poiquery.php?lat=42.349433712876&amp;lon=-71.040894451933&amp;maxfeatures=9&amp;format=application/xml"
        target="_new">http://openpois.net/poiquery.php?lat=42.349433712876&amp;lon=-71.040894451933&amp;maxfeatures=9&amp;format=application/xml</a></p>
    <h4>Bounding box</h4>
    <p><span class="codehl"><a href="http://openpois.net/poiquery.php?bbox=-71.10,42.35,-71.00,42.45&amp;maxfeatures=20&format=application/json"
          target="_new">http://openpois.net/poiquery.php?bbox=-71.10,42.35,-71.00,42.45&amp;maxfeatures=20&format=application/json</a></span></p>
    <h4>Time and area</h4>
    <p class="codehl"><a href="http://openpois.net/poiquery.php?lat=25.959&amp;lon=119.519&amp;maxfeatures=1&amp;format=xml&amp;radius=1000&amp;start=944"
        target="_new">http://openpois.net/poiquery.php?lat=25.959&amp;lon=119.519&amp;maxfeatures=1&amp;format=xml&amp;radius=1000&amp;start=944</a></p>
    <p><br />
    </p>
    <div data-show-faces="true" data-width="450" data-send="true" data-href="http://openpois.net/api.html"
      class="fb-like"></div>
    <p><br />
    </p>

			<?php include('footer.php'); ?>
		</div><!-- end ink-container -->
  </body>
</html>
