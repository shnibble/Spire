<?php
	// get classes
	$stmt->prepare("SELECT `id`,`name` FROM `classes` WHERE `enabled` = TRUE");
	$stmt->execute();
	$classes = $stmt->get_result();
?>
