<?php
	if ($user['rank'] < 1) {
		header("Location: /error.php?id=114");
		exit;
	}
?>
