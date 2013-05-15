<?php 
session_start();

//// do a little security check to see if userid is being set by 
//// a client session that matches this PHP session
if ( session_id() !== $_COOKIE['PHPSESSID'] ) {
	die('not authorized');
}

$_SESSION['uid'] = $_POST['UID'];
echo $_SESSION['uid'];
?>
