<?php
	if ($user['security'] < 2) {
		header("Location: /error.php?id=113");
		exit;
	}
?>
