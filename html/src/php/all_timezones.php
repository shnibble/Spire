<?php
	// get all timezones
	$stmt->prepare("SELECT `id`,`name` FROM `timezones` WHERE 1");
	$stmt->execute();
	$timezones = $stmt->get_result();
?>
