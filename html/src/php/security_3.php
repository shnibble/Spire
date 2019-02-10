<?php
	if ($user['security'] < 3) {
		header("Location: /error.php?id=113");
		exit;
	}
?>
