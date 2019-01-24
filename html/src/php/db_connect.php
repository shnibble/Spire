<?php
	include_once $_SERVER["DOCUMENT_ROOT"] . '/src/config.php';
	$conn = mysqli_connect(HOST, USER, PASSWORD, DATABASE);
	
	if (mysqli_connect_errno()) {
		// ERROR: connection failed
		$error_id = 100;
		header("Location: /error.php?id=" . $error_id);
		
	}
?>
