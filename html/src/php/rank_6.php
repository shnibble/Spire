<?php
	if ($user['rank'] < 6) {
		header("Location: /error.php?id=114");
	}
?>
