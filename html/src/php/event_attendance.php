<?php
	// get event attendance
	$stmt->prepare("SELECT t1.`type`, t2.`name` as typeName, t1.`user_id`, t3.`username`, t3.`rank`, t4.`name` as rankName, t6.`value` FROM `attendance` t1
					INNER JOIN `attendance_types` t2
						ON t2.`id` = t1.`type`
					INNER JOIN `users` t3
						ON t3.`id` = t1.`user_id`
					INNER JOIN `ranks` t4
						ON t4.`id` = t3.`rank`
					INNER JOIN `events` t5
						ON t5.`id` = ?
					INNER JOIN `attendance_values` t6
						ON t6.`event_type_id` = t5.`type` AND t6.`attendance_type_id` = t1.`type`
					WHERE t3.`active` = TRUE AND t1.`attendance_log_id` = (SELECT `attendance_log_id` FROM `events` WHERE `id` = ?) ORDER BY t1.`type`, t3.`username`");
	$stmt->bind_param("ii", $_GET['id'], $_GET['id']);
	$stmt->execute();
	$attendance = $stmt->get_result();
?>
