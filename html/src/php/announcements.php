<?php
	// number of announcements to retrieve
	$announcements_count = 3;
	
	// configure how many articles are expanded by default
	$article_max_expanded = 1; 
	
	// get announcements
	$stmt->prepare("SELECT t1.`id`, t1.`title`, t1.`content`, t1.`timestamp`, t1.`user_id`, t2.`username`
					FROM `announcements` t1
						INNER JOIN `users` t2
						ON t2.`id` = t1.`user_id`
					WHERE t1.`enabled` = TRUE ORDER BY t1.`timestamp` DESC LIMIT ?");
	
	if (!($stmt->bind_param("i", $announcements_count))) {
		// ERROR: failed to bind parameters
		$error = true;
		$error_id = 109;
	} else if (!($stmt->execute())) {
		// ERROR: failed to insert into database
		$error = true;
		$error_id = 109;
	} else {
		$announcements = $stmt->get_result();
	}
?>
