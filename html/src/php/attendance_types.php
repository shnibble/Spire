<?php
	// get attendance types
	$stmt->prepare("SELECT `id`, `name`, `code` FROM `attendance_types` WHERE `enabled` = TRUE ORDER BY `id` ASC");
	$stmt->execute();
	$attendance_types = $stmt->get_result();
?>
