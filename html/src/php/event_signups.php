<?php
	// get event signups
	$stmt->prepare("SELECT t1.`id`, t1.`user_id`, t2.`username`, t1.`character_id`, t3.`name` as characterName, t3.`class`, t1.`character_role`, t4.`name` as roleName, t1.`timestamp`, t1.`note`, t2.`rank`, t5.`name` as rankName, 
					(SELECT COUNT(*) FROM `attendance` WHERE `user_id` = t2.`id` AND `type` = 3) as benchedCount 
					FROM `event_signups` t1
					INNER JOIN `users` t2
						ON t2.`id` = t1.`user_id`
					INNER JOIN `characters` t3
						ON t3.`id` = t1.`character_id`
					INNER JOIN `roles` t4
						ON t4.`id` = t1.`character_role`
					INNER JOIN `ranks` t5
						ON t5.`id` = t2.`rank`
					WHERE t2.`active` = TRUE AND t1.`event_id` = ? AND t1.`type` = 1 ORDER BY t1.`timestamp`");
	$stmt->bind_param("i", $_GET['id']);
	$stmt->execute();
	$signups = $stmt->get_result();
?>
