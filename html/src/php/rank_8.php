<?php
	if ($user['rank'] < 8) {
		header("Location: /error.php?id=114");
	}
?>
