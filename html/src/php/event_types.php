<?php
	// get event types
	$stmt->prepare("SELECT `id`, `name` FROM `event_types` WHERE `enabled` = TRUE ORDER BY `id` ASC");
	$stmt->execute();
	$event_types = $stmt->get_result();
?>
