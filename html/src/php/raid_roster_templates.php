<?php
	// get raid roster templates
	$stmt->prepare("SELECT `id`, `name` FROM `raid_roster_templates` WHERE `enabled` = TRUE");
	$stmt->execute();
	$raid_roster_templates = $stmt->get_result();
?>
