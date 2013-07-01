window.onload = init;

function init() {
  // if (Modernizr.geolocation) navigator.geolocation.getCurrentPosition(getRandomPOI);
	navigator.geolocation.getCurrentPosition(getRandomPOI);
}

function getRandomPOI(position) {
  var lat = position.coords.latitude;
  var lon = position.coords.longitude;
	window.location = "/randompoi.php?lat="+lat+"&lon="+lon;
}
