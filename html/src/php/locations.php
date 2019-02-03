<?php
	// get locations
	$stmt->prepare("SELECT `id`,`name`, `abbreviation`, `event_type_id` FROM `locations` WHERE `enabled` = TRUE");
	$stmt->execute();
	$locations = $stmt->get_result();
?>
