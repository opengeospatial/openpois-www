<?php

function getButton($referer) {
	$loginurl = 'http://' . $_SERVER['HTTP_HOST'] . '/login/index.php';
	$loginurl .= '?referer=' . $referer;
	$loggedin = TRUE;
	$info = 'Sign in to add names, descriptions, categories and links';
	
	if ( empty($_COOKIE["uid"]) ) $loggedin = FALSE;

	$h =  "<div id=\"login\">\n";
	$h .= "<button type=\"button\" id=\"loginoutbutton\" class=\"ink-button info\" tooltip=\"$info\"";
	if ( $loggedin ) 
		$h .= " onclick=\"window.location.href='/logout.php';\">Sign out</button>\n";
	else 
		$h .= " onclick=\"window.location.href='" . $loginurl . "';\">Sign in</button>\n";
		
	$h .= "</div>\n";
	
	return $h;
}
?>
	
