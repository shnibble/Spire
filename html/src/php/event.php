<?php
	// get event
	$stmt->prepare("SELECT t1.`id`, t1.`title`, t1.`description`, t1.`start`, t1.`type`, t1.`notify_late_signups`, t2.`name` as typeName, t1.`leader_id`, t3.`username` as leaderName, t1.`looter_id`, t4.`username` as looterName, t1.`buff_instructions`, t1.`meetup_instructions`, t1.`log_attendance`, t1.`attendance_log_id` 
					FROM `events` t1
						INNER JOIN `event_types` t2
						ON t2.`id` = t1.`type`
						LEFT JOIN `users` t3
						ON t3.`id` = t1.`leader_id`
						LEFT JOIN `users` t4
						ON t4.`id` = t1.`looter_id`					
					WHERE t1.`id` = ? AND t1.`enabled` = TRUE");
	$stmt->bind_param("i", $_GET['id']);
	$stmt->execute();
	$result = $stmt->get_result();
	
	if (mysqli_num_rows($result) == 1) {
		$valid_event = true;
		$event = mysqli_fetch_array($result);
	} else {
		$valid_event = false;
	}
	
	?>
