<?php
	// get occasion types
	$stmt->prepare("SELECT `id`,`name` FROM `occasion_types` WHERE `enabled` = TRUE");
	$stmt->execute();
	$occasion_types = $stmt->get_result();
?>
