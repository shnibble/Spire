<?php
	if ($user['rank'] < 2) {
		header("Location: /error.php?id=114");
		exit;
	}
?>
