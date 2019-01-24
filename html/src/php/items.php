<?php
	// get items
	$stmt->prepare("SELECT `id`,`name`, `quality`, `default_type`, `loot_priority`, `comment` FROM `items` WHERE `enabled` = TRUE ORDER BY `name`");
	$stmt->execute();
	$items = $stmt->get_result();
?>
