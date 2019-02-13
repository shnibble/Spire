<?php
	// get security
	$stmt->prepare("SELECT `id`,`name`, `description` FROM `security` WHERE 1 ORDER BY `id`");
	$stmt->execute();
	$security = $stmt->get_result();
?>
