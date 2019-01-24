<?php
	// get user signups by event
	$stmt->prepare("SELECT t1.`id`, t1.`username`, t1.`rank`, t2.`name` as rankName, (SELECT `type` FROM `event_signups` WHERE `event_id` = ? AND `user_id` = t1.`id`) as signupStatus, (SELECT `note` FROM `event_signups` WHERE `event_id` = ? AND `user_id` = t1.`id`) as signupNote, (SELECT GROUP_CONCAT(`name`) FROM `characters` WHERE `enabled` = TRUE AND `user_id` = t1.`id`) as characters
					FROM `users` t1
						INNER JOIN `ranks` t2
						ON t2.`id` = t1.`rank`
					WHERE t1.active = TRUE AND t1.`rank` > 1 ORDER BY t1.`username`");
	$stmt->bind_param("ii", $_POST['event'], $_POST['event']);
	$stmt->execute();
	$user_event_signups = $stmt->get_result();
?>
