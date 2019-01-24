<?php
	// get characters
	$stmt->prepare("SELECT `id`,`name`, `user_id`, `class`, `role` FROM `characters` WHERE `enabled` = TRUE AND `user_id` IN (SELECT `id` FROM `users` WHERE `active` = TRUE) ORDER BY `name`");
	$stmt->execute();
	$characters = $stmt->get_result();
?>
