<?php
	if ($user['rank'] < 4) {
		header("Location: /error.php?id=114");
	}
?>
