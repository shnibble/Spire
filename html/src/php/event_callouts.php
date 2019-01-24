<?php
	// get event callouts
	$stmt->prepare("SELECT t1.`id`, t1.`user_id`, t2.`username`, t1.`timestamp`, t1.`note`, t2.`rank`, t3.`name` as rankName FROM `event_signups` t1
					INNER JOIN `users` t2
						ON t2.`id` = t1.`user_id`
					INNER JOIN `ranks` t3
						ON t3.`id` = t2.`rank`
					WHERE t2.`active` = TRUE AND t1.`event_id` = ? AND t1.`type` = 2 ORDER BY t1.`timestamp`");
	$stmt->bind_param("i", $_GET['id']);
	$stmt->execute();
	$callouts = $stmt->get_result();
?>
