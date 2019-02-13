<?php
	if ($user['security'] < 1) {
		header("Location: /error.php?id=113");
		exit;
	}
?>
