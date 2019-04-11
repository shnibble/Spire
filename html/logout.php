<?php 
	session_start();
	setcookie('login_token', '', time() - 3600, '/');
	session_unset();
	session_destroy();
	header("Location: /");
	exit;
?>
