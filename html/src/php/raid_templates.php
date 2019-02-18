<?php
	// get raid templates
	$stmt->prepare("SELECT `id`, `name` FROM `raid_template` WHERE `enabled` = TRUE");
	$stmt->execute();
	$raid_templates = $stmt->get_result();
?>
