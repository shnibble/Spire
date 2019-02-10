<?php
	if ($user['rank'] < 7) {
		header("Location: /error.php?id=114");
		exit;
	}
?>
