<?php
	// get loot types
	$stmt->prepare("SELECT `id`,`name` FROM `loot_types` WHERE `enabled` = TRUE");
	$stmt->execute();
	$loot_types = $stmt->get_result();
?>
