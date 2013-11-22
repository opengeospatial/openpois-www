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
			<?php include('footer.php'); ?>
    </div>
	<div class="ink-container">
		<h1>Web Services API</h1>
		<p>OpenPOIs has two APIs available for use. It implements the industry standard <a href="#wfsapi">Web Feature Service API</a> for geographic data query, and also has its <a href="#customapi">own custom API</a>.</p>
		
		<h2><a name="customapi"></a>Custom API</h2>
    <h3>Request base URL</h3>
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
          <td><code>id</code></td>
          <td><em>UUID</em></td>
          <td>globally unique id of the POI<br />
            <em>if present, all other parameters (except format) are ignored</em></td>
        </tr>
        <tr>
          <td><code>format</code></td>
          <td><em>string<br />
            </em></td>
          <td>MIME type of the return value<br />
              <strong>options:</strong> <code>text/html</code>, <code>geojson</code>, <code>application/json</code> or <code>application/xml</code><br />
              <strong>default:</strong> <code>text/html</code>
          </td>
        </tr>
        <tr>
          <td><code>name</code></td>
          <td><em>string</em></td>
          <td>primary name/label of the POI<br />
            <em>required if id is not present</em></td>
        </tr>
        <tr>
          <td><code>bbox</code></td>
          <td><em>comma-separated decimals</em></td>
          <td>geographic bounding box in EPSG:4326 (lat/lon)<br />
            <strong>format:</strong> left,lower,right,upper<br />
            <em>if present, lat/lon/radius are ignored</em></td>
        </tr>
        <tr>
          <td><code>lat</code></td>
          <td><em>decimal</em></td>
          <td>latitude<br />
            <em>required for lat/lon/radius search</em></td>
        </tr>
        <tr>
          <td><code>lon</code></td>
          <td><em>decimal</em></td>
          <td>longitude<br />
            <em>required for lat/lon/radius search</em></td>
        </tr>
        <tr>
          <td><code>radius</code></td>
          <td><em>decimal</em></td>
          <td>search distance from lat/lon in meters<br />
              <em>used only for lat/lon/radius search<br />
              </em><strong>default:</strong> 50
          </td>
        </tr>
        <tr>
          <td><code>start</code></td>
          <td><em>date</em></td>
          <td>POI came into existence after this date (POIs with no start date
            are included)<br />
            <strong>ex:</strong> 2011-10-01</td>
        </tr>
        <tr>
          <td><code>end</code></td>
          <td><em>date</em></td>
          <td>POI ended existence before this date (POIs with no end date are
            included)<br />
            <strong>ex:</strong> 2012-05-01</td>
        </tr>
        <tr>
          <td><code>maxfeatures</code></td>
          <td><em>integer</em></td>
          <td>maximum number of POIs to return<br />
            <strong>default:</strong> 25 (maximum allowed is 25 also)</td>
        </tr>
      </tbody>
    </table>
    <h3>Query Examples</h3>
    <h4>ID</h4>
    <p class="codehl"><a href="http://openpois.net/poiquery.php?id=a75da8b9-5e85-46f2-9546-35c87ef0dda3&amp;format=application/json"
        target="_new">http://openpois.net/poiquery.php?id=a75da8b9-5e85-46f2-9546-35c87ef0dda3&amp;format=application/json
        </a></p>
    <h4>RESTful ID</h4>
    <p class="codehl"><a href="http://openpois.net/pois/a75da8b9-5e85-46f2-9546-35c87ef0dda3"
        target="_new">http://openpois.net/pois/a75da8b9-5e85-46f2-9546-35c87ef0dda3</a></p>
    <p class="codehl"><a href="http://openpois.net/pois/a75da8b9-5e85-46f2-9546-35c87ef0dda3.geojson"
        target="_new">http://openpois.net/pois/a75da8b9-5e85-46f2-9546-35c87ef0dda3.geojson</a></p>
    <p class="codehl"><a href="http://openpois.net/pois/a75da8b9-5e85-46f2-9546-35c87ef0dda3.json"
        target="_new">http://openpois.net/pois/a75da8b9-5e85-46f2-9546-35c87ef0dda3.json</a></p>
	  <p class="codehl"><a href="http://openpois.net/pois/a75da8b9-5e85-46f2-9546-35c87ef0dda3.xml"
     		target="_new">http://openpois.net/pois/a75da8b9-5e85-46f2-9546-35c87ef0dda3.xml</a></rdf>
	  <p class="codehl"><a href="http://openpois.net/pois/a75da8b9-5e85-46f2-9546-35c87ef0dda3.rdf"
     		target="_new">http://openpois.net/pois/a75da8b9-5e85-46f2-9546-35c87ef0dda3.rdf</a> (in <a href="https://github.com/pelagios/pelagios-cookbook/wiki/Pelagios-Gazetteer-Interconnection-Format" target="new"><em>Pelagios Gazetteer Interconnection format</em></a>)</p>
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

		<h2><a name="wfsapi"></a>OGC Web Feature Service (WFS) API</h2>
		<p>OpenPOIs implements the <a href="http://www.opengeospatial.org/standards/wfs">the OGC WFS standard</a> using <a href="http://mapserver.org/ogc/wfs_server.html">Mapserver's implementation</a>. WFS is a full-featured API with many more options than are shown here, but here are some examples on how to use it. For more information, see the <a href="http://www.opengeospatial.org/standards/wfs">spec</a> or <a href="http://mapserver.org/ogc/wfs_server.html">Mapserver's documentation</a>.</p>
		<p><b>NOTE: </b><em>Due to the complex nature of the POI schema, the WFS API only recognizes a few fields of a POI -- the POI's ID, primary label and geographic coordinates. Until more work is done to expand the capabilities of the WFS API, use the custom API above to get all the POI data, or use the WFS API, and then take the POI ID contained in WFS responses to query against the custom API when you need full POI data.</em><p>

		<h4>GetCapabilities</h4>
		<p><span class="codehl"><a href="/openpoiwfs?request=GetCapabilities&service=WFS&version=1.1.0">http://openpois.net/openpoiwfs?request=GetCapabilities&amp;service=WFS&amp;version=1.1.0</a></p>

		<h4>DescribeFeatureType (see the GML schema)</h4>
		<p><span class="codehl"><a href="/openpoiwfs?request=DescribeFeatureType&service=WFS&version=1.1.0&typename=minipoi">http://openpois.net/openpoiwfs?request=DescribeFeatureType&amp;service=WFS&amp;version=1.1.0&amp;typename=minipoi</a></p>

		<h4>Get a single POI by ID</h4>
		<p><span class="codehl"><a href="/openpoiwfs?request=GetFeature&service=WFS&version=1.1.0&typename=minipoi&featureid=minipoi.a75da8b9-5e85-46f2-9546-35c87ef0dda3">http://openpois.net/openpois.net/openpoiwfs?request=GetFeature&amp;service=WFS&amp;version=1.1.0&amp;typename=minipoi&amp;featureid=minipoi.a75da8b9-5e85-46f2-9546-35c87ef0dda3</a></p>
			
		<h4>Get a group of POIs by bounding box</h4>
		<p><span class="codehl"><a href="/openpoiwfs?request=GetFeature&service=WFS&version=1.1.0&typename=minipoi&bbox=-71.05,42.40,-71.00,42.45&srsname=epsg:4326">http://openpois.net/openpoiwfs?request=GetFeature&amp;service=WFS&amp;version=1.1.0&amp;typename=minipoi&amp;bbox=-71.05,42.40,-71.00,42.45&amp;srsname=epsg:4326</a></p>

    <p><br />
    </p>

    <div data-show-faces="true" data-width="450" data-send="true" data-href="http://openpois.net/api.html"
      class="fb-like"></div>
	    <p><br />
	    </p>

		</div><!-- end ink-container -->
  </body>
</html>
