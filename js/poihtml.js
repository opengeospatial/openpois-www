function addTag() {
  id = $('a[itemprop="url"]').html();
  req = "id="+id;

  term = $('#category-term').val();
  if (!term) {
    alert('Term is required');
    return false;
  }
  req += "&term="+term;
  
  scheme = $('#category-scheme').val();
  if ( scheme ) {
    if ( /^http:\/\//i.test(scheme) ||/^https:\/\//i.test(scheme) ) {
      req += "&scheme="+scheme;
    } else {
      alert("Scheme must be a URI starting with http:// or https://");
      return false;
    }
  }

  value = $('#category-value').val();
  if (value) {
    req += "&value="+value;
  }

  // Now submit
  req = "type=category&" + req;
  var f = $.ajax({
      type: "GET", 
      url: "http://api.flickr.com/services/rest", 
      data: req, 
      dataType: "json", 
      success: function(data, textStatus, jqXHR) {
        appendFlickr(data); // ajax request is for JSON format, so data is auto-converted to an object
      }, 
      fail: function(data, textStatus, jqXHR) {
        alert("flickr search failed: "+textStatus);
      }
  });
}

function displayPOI(map, zoomtopoi) {
  
}

/** 
 * @param map Leaflet map object true if the map is also displayed (show fewer Flickr pix) 
 * @param zoomtopoi
 */
function displayPics(lat, lon, numpix) {
  numpix = (typeof numpix === 'undefined') ? 5 : numpix;
  zoomtopoi = (typeof zoomtopoi !== 'undefined') ? zoomtopoi : false;

  // Now load flickr pics
  req = "method=flickr.photos.search&format=json&api_key=a52bddc9b8e8be0556b8bd2a210f75e3";
  req += "&nojsoncallback=1&per_page="+numpix;
  req += "&lat="+lat+"&lon="+lon+"&radius=.25&radius_units=km&min_upload_date=1999-02-02";

  var f = $.ajax({
      type: "GET", 
      url: "http://api.flickr.com/services/rest", 
      data: req, 
      // url: "http://localhost/openpoi/www/t.json", 
      dataType: "json", 
      success: function(data, textStatus, jqXHR) {
        appendFlickr(data); // ajax request is for JSON format, so data is auto-converted to an object
      }, 
      fail: function(data, textStatus, jqXHR) {
        alert("flickr search failed: "+textStatus);
      }
  });
  
}

function appendFlickr(data) {
  h = "<p class=\"subhead\">flickr pictures taken nearby</p>";
  
  if ( data.stat != "ok") {
    $("#message").html("something broke");
    return;
  }

  for (var i=0; i<data.photos.photo.length; i++) {
    p = data.photos.photo[i];
    // purl = buildFlickrURL(null, p.id, p.server, p.farm, p.secret);
    purl = "http://www.flickr.com/photos/" + p.owner + "/" + p.id;
    purls = buildFlickrURL('s', p.id, p.server, p.farm, p.secret);
    h += "<div class=\"pic\"><a href=\""+purl+"\" target=\"_blank\">";
    h += "<img src=\""+purls+"\" alt=\"" + p.title + "\"></a>";
    h += "<div class=\"piccaption\">" + p.title + "</div></div>";
  }

  $("#pictures").html(h);  
}

function buildFlickrURL(size, id, serverid, farmid, secret) {
  u = "http://farm" + farmid + ".staticflickr.com/" + serverid + "/" + id + "_" + secret;
  if ( size == null ) 
    u += ".jpg";
  else 
    u += "_" + size + ".jpg";
  return u;
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

