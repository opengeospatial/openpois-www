function FBlogin() {
    FB.login(function(response) {
        if (response.authResponse) {
            // connected
      			expire = new Date();
      			expire.setTime( expire.getTime() + 3600000*24);
      			document.cookie = 'uid=' + 
      								response.authResponse.userID + '@facebook.com' + 
      								';expires=' + expire.toGMTString();
      			window.setTimeout('location.reload()', 1000);
        } else {
            // cancelled
        }
    });
}
