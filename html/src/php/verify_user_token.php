<?php
	if ($_SESSION['token'] != $user['token']) {
		// ERROR: invalid token
		$error_id = 115;
		header("Location: /error.php?id=" . $error_id);
		
	}
?>
