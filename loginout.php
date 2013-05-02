<?php

function getButton($referer) {
	$loginurl = 'http://' . $_SERVER['HTTP_HOST'] . '/login/index.php';
	$loginurl .= '?referer=' . $referer;
	$loggedin = TRUE;
	if ( empty($_COOKIE["uid"]) ) $loggedin = FALSE;

	$h =  "<div id=\"login\">\n";
	$h .= "<button type=\"button\" id=\"loginoutbutton\"";
	if ( $loggedin ) 
		$h .= " onclick=\"window.location.href='/logout.php';\">log out</button>\n";
	else 
		$h .= " onclick=\"window.location.href='" . $loginurl . "';\">log in</button>\n";
	$h .= "</div>\n";
	
	return $h;
}
?>
	
