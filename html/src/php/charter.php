<?php
	// get charter
	$stmt->prepare("SELECT `id`, `section`, `level`, `title`, `content` FROM `charter` ORDER BY `section`");
	if (!($stmt->execute())) {
		// ERROR: failed to insert into database
		$error = true;
		$error_id = 109;
	} else {
		$charter = $stmt->get_result();
	}
?>
