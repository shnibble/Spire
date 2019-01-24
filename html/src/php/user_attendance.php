<?php
	// get user attendance
	$stmt->prepare("SELECT t1.`id`, t1.`attendance_log_id`, t2.`title`, t2.`date`, t3.`name` as event_type, t1.`type` as attendance_type, t4.`name` as attendance_name, t5.`value`
					FROM `attendance` t1
						INNER JOIN `attendance_log` t2
							ON t2.`id` = t1.`attendance_log_id`
						INNER JOIN `event_types` t3
							ON t3.`id` = t2.`type`
						INNER JOIN `attendance_types` t4
							ON t4.`id` = t1.`type`
						INNER JOIN `attendance_values` t5
							ON t5.`event_type_id` = t2.`type` AND t5.`attendance_type_id` = t1.`type`
					WHERE t1.`user_id` = ? ORDER BY t2.`date` DESC");
	$stmt->bind_param("i", $_SESSION['user_id']);
	$stmt->execute();
	$user_attendance = $stmt->get_result();
?>
