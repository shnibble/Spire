<?php
	// get users
	$stmt->prepare("SELECT t1.`id`, t1.`active`, t1.`username`, t1.`joined`, t1.`last_login`, t1.`rank`, t2.`name` as rankName, t1.`security`, t1.`timezone_id`, t3.`name` as timezoneName, t4.`name` as characterName, (SELECT GROUP_CONCAT(`badge_id` ORDER BY `badge_id`) FROM `user_badges` WHERE `user_id` = t1.`id`) badges, t5.`loot_cost`, t5.`attendance_score`, (t5.`attendance_score` - t5.`loot_cost`) spread, t5.`30_day_attendance_rate` 
					FROM `users` t1
						INNER JOIN `ranks` t2
							ON t2.`id` = t1.`rank`
						INNER JOIN `timezones` t3
							ON t3.`id` = t1.`timezone_id`
						INNER JOIN `characters` t4
							ON t4.`user_id` = t1.`id` AND t4.`main` = TRUE
						INNER JOIN `user_scores` t5 
							ON t5.`user_id` = t1.`id` 
					WHERE t1.`active` = TRUE AND t1.`rank` > 1 ORDER BY t1.`username`");
	$stmt->execute();
	$users = $stmt->get_result();
?>
