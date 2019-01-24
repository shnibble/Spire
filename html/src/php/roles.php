<?php
	// get roles
	$stmt->prepare("SELECT `id`,`name` FROM `roles` WHERE `enabled` = TRUE");
	$stmt->execute();
	$roles = $stmt->get_result();
?>
