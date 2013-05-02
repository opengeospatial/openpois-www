<?php
	session_start();
	setcookie("uid", FALSE, 0, '/');
	
	$lf = '/';

	if ( !empty($_SESSION['loginreferrer']) ) {
		$lf = $_SESSION['loginreferrer'];
		unset($_SESSION['loginreferrer']);

	} else if ( !empty($_SERVER['HTTP_REFERER']) ) {
		$lf = $_SERVER['HTTP_REFERER'];
	}

	header('Location:' . $lf);
?>
