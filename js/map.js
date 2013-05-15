var map;
var popup;
var ogcurl = "http://" + document.domain + "/openpoiwms?";
var epsg3857 = new OpenLayers.Projection("EPSG:3857");
var epsg4326 = new OpenLayers.Projection("EPSG:4326");

$(document).ready(function() {
  initmap();
  
  $('#searchsubmit').click(function() { // validate and process form here
    var name = $('#searchname').val();

    if ( name == "") {
      alert("Enter a place name before searching");
      $('#searchname').focus();
      return false;
    }
    
    handleNameSearch(name);
    return false;
  });
});

function queryFail(data, textStatus, jqXHR) {
  $('#map').css('cursor','default');
  // map.popups[0].destroy();
  errormsg = jQuery.parseJSON(data.responseText);
  alert("Name search query failed: "+errormsg.err.message+"\n"+errormsg.err.query);
}

function handleNameSearch(name) {
  var ex = map.getExtent().transform(map.getProjectionObject(), epsg4326);
  var bbox = ex.toBBOX();
  var q = $.ajax({
    dataType: "json", 
    type: "GET", 
    url: "poiquery.php", 
    data: "name="+name+"&bbox="+bbox+"&maxfeatures=1&format=application/json&output=brief", 
    error: queryFail, 
    success: showPopUp
  });
  
  
  // ajax request was for JSON format, so data is auto-converted to an object
  // q.success(function(data, textStatus, jqXHR) {
  //   displayPOI(data, map, true);
  // });  
  // q.success = showPopUp; // ajax request was for JSON format, so data is auto-converted to an object
}

function handleMapId(id) {
  var q = $.ajax({
      dataType: "json", 
      type: "GET", 
      url: "poiquery.php", 
      data: "id="+id+"&maxfeatures=1&format=application/json&output=brief", 
      error: queryFail, 
      success: showPopUp
  });
}

function handleClick(lonlat) {
  $('#map').css('cursor','wait');
  
  var q = $.ajax({
      dataType: "json", 
      type: "GET", 
      url: "poiquery.php", 
      data: "lat="+lonlat.lat+"&lon="+lonlat.lon+"&maxfeatures=1&format=application/json&output=brief", 
      error: queryFail, 
      success: showPopUp
  });
}

function showPopUp(data, textStatus, jqXHR) {
  try {
    lon = data.pois[0].lon;
    lat = data.pois[0].lat;

    lonlat3857 = new OpenLayers.LonLat(lon, lat).transform(epsg4326, map.getProjectionObject());
    h = '<a class="popup" target="poiwin" href="' + data.pois[0].id + '">' + data.pois[0].name + '</a>';
    map.addPopup(new OpenLayers.Popup.FramedCloud(
        "chicken", 
        lonlat3857, 
        null,
        h,
        null,
        null
    ), true);

    $('#map').css('cursor','default');
  } catch (e) {
    alert(data);
  }
}

function initmap() {
  OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {                
       defaultHandlerOptions: {
           'single': true,
           'double': false,
           'pixelTolerance': 0,
           'stopSingle': false,
           'stopDouble': false
       },

       initialize: function(options) {
           this.handlerOptions = OpenLayers.Util.extend(
               {}, this.defaultHandlerOptions
           );
           OpenLayers.Control.prototype.initialize.apply(
               this, arguments
           ); 
           this.handler = new OpenLayers.Handler.Click(
               this, {
                   'click': this.trigger
               }, this.handlerOptions
           );
       }, 

       trigger: function(e) {
         var lonlat = map.getLonLatFromViewPortPx(e.xy);
         lonlat = OpenLayers.Layer.SphericalMercator.inverseMercator(lonlat.lon, lonlat.lat);
         handleClick(lonlat);
       }

   });
   
   
  map = new OpenLayers.Map({
    div: 'map', 
    projection: epsg3857, 
    displayProjection: epsg4326, 
    numZoomLevels: 18,
    controls: [
      new OpenLayers.Control.TouchNavigation({
          dragPanOptions: {
              enableKinetic: true
          }
      }), 
      new OpenLayers.Control.PanZoomBar(), 
      // geolocate
    ]
  });
  
  map.addControl(new OpenLayers.Control.MousePosition({
    prefix: 'lon, lat: ', 
    separator: ', ',
    numDigits: 4, 
    emptyString: ' '
    })
  );

    
  // create a CloudMade tile layer
  var cloudmade = new OpenLayers.Layer.CloudMade("Cloudmade", {
    key: '9c4b6a87177e4ae1b8736d5e9d656d96', 
    styleId: 998 // 4 or 7 nice too
  })
  
	// POI layer
  var minipoi = new OpenLayers.Layer.WMS("OpenPOIs minipoi", ogcurl, 
    {
      layers: 'minipoi,poipt', transparent: 'true'//, format: 'image/png', visibility: 'true'
    }, {
      singleTile: true, 
      isBaseLayer: false
    }
  );

  map.addLayers( [minipoi, cloudmade] );
  var click = new OpenLayers.Control.Click();
  map.addControl(click);
  click.activate();

  // -71,42.55 is around Georgetown, MA
  // -71.087927,42.362777 is around MIT
  // -71.40922, 41.8176 is Providence, RI
  // 51.505, -0.09 is leaflet example
  // 12.481469, 41.87667 is in Italy near Rome
  // map.setCenter( new OpenLayers.LonLat(12.481469, 41.87667).transform(epsg4326, map.getProjectionObject()), 15);
  map.setCenter( new OpenLayers.LonLat(-71.40922, 41.8176).transform(epsg4326, map.getProjectionObject()), 15);
  
  var id = getUrlVars()["id"];
  if ( id != null ) {
    handleMapId(id);
  }

  if ( !id ) {
    navigator.geolocation.getCurrentPosition(function(position) {  
      lg = position.coords.longitude;
      lt = position.coords.latitude;
      var lonLat = new OpenLayers.LonLat(lg, lt).transform(
          epsg4326, //transform from WGS 1984
          map.getProjectionObject() //to Spherical Mercator Projection
        );
    
      map.setCenter(lonLat, 15);
    });
  }
}

function getUrlVars() {
  var vars = {};
  var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
          vars[key] = value;
      });
  return vars;
}

