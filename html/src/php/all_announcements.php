<?php
	// get all announcements
	$stmt->prepare("SELECT t1.`id`, t1.`title`, t1.`content`, t1.`timestamp`, t1.`user_id`, t2.`username` 
					FROM `announcements` t1
						INNER JOIN `users` t2
						ON t2.`id` = t1.`user_id`
					WHERE t1.`enabled` = TRUE ORDER BY t1.`timestamp` DESC LIMIT 10 OFFSET ?");
	
	if (!($stmt->bind_param("i", $_GET['offset']))) {
		// ERROR: failed to bind parameters
		$error = true;
		$error_id = 109;
	} else if (!($stmt->execute())) {
		// ERROR: failed execute
		$error = true;
		$error_id = 109;
	} else {
		$all_announcements = $stmt->get_result();
	}
?>
