<?php
	if ($user['rank'] < 3) {
		header("Location: /error.php?id=114");
		exit;
	}
?>
