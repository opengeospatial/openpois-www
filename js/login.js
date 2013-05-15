function FBlogin() {
  // alert('PHPSESSID='+$.cookie('PHPSESSID') );
  
  FB.login(function(response) {
      if (response.authResponse) {
          // connected
    			expire = new Date();
    			expire.setTime( expire.getTime() + 3600000*24);
          // document.cookie = 'uid=' + 
          //          response.authResponse.userID + '@facebook.com' + 
          //          ';expires=' + expire.toGMTString();
          uidval = response.authResponse.userID + '@facebook.com';
          $.post("/login/setuid.php", {UID: uidval} );
          // alert('posted uid: '+uidval);

          window.setTimeout('location.reload(true)', 1000);
      } else {
          // cancelled
      }
  });
}

