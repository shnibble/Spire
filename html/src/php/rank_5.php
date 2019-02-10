<?php
	if ($user['rank'] < 5) {
		header("Location: /error.php?id=114");
		exit;
	}
?>
