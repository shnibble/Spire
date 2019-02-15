<?php
	// get unlogged events
	$stmt->prepare("SELECT t1.`id`, t1.`title`, t1.`description`, DATE_FORMAT(t1.`start`, '%Y-%m-%d %H:%i') as start, t1.`type`, t2.`name` as typeName
					FROM `events` t1
						INNER JOIN `event_types` t2
						ON t2.`id` = t1.`type`
					WHERE t1.`attendance_log_id` IS NULL AND t1.`log_attendance` = TRUE AND t1.`start` < NOW() ORDER BY t1.`start` ASC");
	$stmt->execute();
	$uEvents = $stmt->get_result();
?>
