<?php
	// get user
	$stmt->prepare("SELECT t1.`username`, t1.`token`, t1.`joined`, t1.`rank`, t2.`name` as rank_name, t1.`security`, t3.`name` as security_name, t3.`description` as security_description, t1.`timezone_id`, t4.`name` as `timezone_name`, (SELECT GROUP_CONCAT(`badge_id` ORDER BY `badge_id`) FROM `user_badges` WHERE `user_id` = t1.`id`) badges 
					FROM `users` t1
						INNER JOIN `ranks` t2
						ON t2.`id` = t1.`rank`
						INNER JOIN `security` t3
						ON t3.`id` = t1.`security`
						INNER JOIN `timezones` t4
						ON t4.`id` = t1.`timezone_id`
					WHERE t1.`id` = ?");
	$stmt->bind_param("i", $_SESSION['user_id']);
	$stmt->execute();
	$result = $stmt->get_result();
	$user = mysqli_fetch_array($result);
?>
