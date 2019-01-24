<?php
	// get users
	$stmt->prepare("SELECT t1.`id`, t1.`active`, t1.`username`, t1.`joined`, t1.`last_login`, t1.`rank`, t2.`name` as rankName, t1.`security`, t1.`timezone_id`, t3.`name` as timezoneName, t4.`name` as characterName, 
					(SELECT SUM(t7.`value`)
						FROM `attendance` t5
						INNER JOIN `attendance_log` t6
							ON t6.`id` = t5.`attendance_log_id`
						INNER JOIN `attendance_values` t7
							ON t7.`event_type_id` = t6.`type` AND t7.`attendance_type_id` = t5.`type`
						WHERE t5.`user_id` = t1.`id`) as attendanceScore,
					(SELECT SUM(t9.`cost`) 
						FROM `loot` t8
						INNER JOIN `loot_types` t9 
							ON t9.`id` = t8.`type`
						WHERE t8.`enabled` = TRUE AND t8.`character_id` IN (SELECT `id` FROM `characters` WHERE `user_id` = t1.`id`)) as lootScore 
					FROM `users` t1
						INNER JOIN `ranks` t2
							ON t2.`id` = t1.`rank`
						INNER JOIN `timezones` t3
							ON t3.`id` = t1.`timezone_id`
						INNER JOIN `characters` t4
							ON t4.`user_id` = t1.`id` AND t4.`main` = TRUE
					WHERE 1 ORDER BY t1.`username`");
	$stmt->execute();
	$users = $stmt->get_result();
?>
