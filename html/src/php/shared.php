<?php
	// get page name
	$pageName = explode(".", basename($_SERVER['PHP_SELF']))[0];
	
	// start session
	$lifetime = 10800; // set timout to 3 hours
	session_start();
	setcookie(session_name(), session_id(), time()+$lifetime, "/");
	
	// verify session
	if( (!isset($_SESSION['user_id']) || ($_SESSION['user_id'] == "")) || 
		(!isset($_SESSION['user_name']) || ($_SESSION['user_name'] == "")) || 
		(!isset($_SESSION['token']) || ($_SESSION['token'] == "")) ) {
			
		session_unset();
		session_destroy();
		header("Location: /sessionTimeout.html");
		exit;
	}
?>
