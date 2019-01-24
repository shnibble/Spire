<?php
	// get player attendance
	$stmt->prepare("SELECT t1.`id`, t1.`attendance_log_id`, t2.`title`, t2.`date`, t3.`name` as eventType, t1.`type` as attendanceType, t4.`name` as attendanceName, t5.`value`
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
	$stmt->bind_param("i", $_GET['id']);
	$stmt->execute();
	$player_attendance = $stmt->get_result();
?>
