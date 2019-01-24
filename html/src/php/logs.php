<?php
	// number of logs to retrieve
	$logs_count = 100;

	// get logs
	$stmt->prepare("SELECT t1.`id`, t1.`user_id`, t2.`username`, t1.`timestamp`, t1.`description` FROM `log` t1
					INNER JOIN `users` t2
						ON t2.`id` = t1.`user_id`
					WHERE t1.`security_level` <= ? ORDER BY t1.`timestamp` DESC LIMIT ?");
	$stmt->bind_param("ii", $user['security'], $logs_count);
	$stmt->execute();
	$logs = $stmt->get_result();
?>
