<?php
	// get security
	$stmt->prepare("SELECT `id`,`name`, `description` FROM `security` WHERE 1");
	$stmt->execute();
	$security = $stmt->get_result();
?>
