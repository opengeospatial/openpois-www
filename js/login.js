function FBlogin() {
  
  FB.login(function(response) {
      if (response.authResponse) {
          // connected
    			expire = new Date();
    			expire.setTime( expire.getTime() + 3600000*24);
          // document.cookie = 'uid=' + 
          //          response.authResponse.userID + '@facebook.com' + 
          //          ';expires=' + expire.toGMTString();
  			  login(response);
      } else {
          // cancelled
      }
  });
}

function getLoginStatus() {
  // if already signed in, as indicated by button saying 'Sign out', then return
  if ( $('#loginoutbutton').html() == 'Sign out') return;
  
  FB.getLoginStatus(function(response) {
	  if (response.status === 'connected') {
	    // connected
			if ( confirm('You are logged into Facebook.\nUse your Facebook ID as your OpenPOIs ID?') ) {
			  login(response);
			}
	  } else if (response.status === 'not_authorized') {
	    // not_authorized
	  } else {
	    // not_logged_in
	  }
	 });
}

function login(response) {
  uidval = response.authResponse.userID + '@facebook.com';
  // $.post("/login/setuid.php", {UID: uidval} );
  $.ajax({
    type: "POST", 
    url: "/login/setuid.php", 
    data: {UID: uidval}, 
    async: true
  })
  
  if ( getURLParameter('referer') ) {
    window.location.href = getURLParameter('referer');
  } else {
    window.setTimeout('location.reload(true)', 1000);
  }

}

function getURLParameter(name) {
    return decodeURIComponent(
      (new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20')
      ) || null;
}
