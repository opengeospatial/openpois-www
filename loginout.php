<?php
session_start();
$loggedin = TRUE;
// if ( empty($_COOKIE["uid"]) ) $loggedin = FALSE;
if ( empty($_SESSION['uid']) ) $loggedin = FALSE;

function getButton($referer) {
	global $loggedin;
	$loginurl = 'http://' . $_SERVER['HTTP_HOST'] . '/login/index.php';
	$loginurl .= '?referer=' . $referer;
	$info = 'Sign in to add names, descriptions, categories and links';
	
	$h =  "<div id=\"login\">\n";
	if ( $loggedin ) $h .= '<div>' . $_SESSION['uid'] . '</div>';
	$h .= "<button type=\"button\" id=\"loginoutbutton\" class=\"ink-button info\" tooltip=\"$info\"";
	if ( $loggedin ) 
		$h .= " onclick=\"window.location.href='/logout.php';\">Sign out</button>\n";
	else 
		$h .= " onclick=\"window.location.href='" . $loginurl . "';\">Sign in</button>\n";
		
	$h .= "</div>\n";
	
	return $h;
}
?>
	
