<?php
	// get user characters
	$stmt->prepare("SELECT t1.`id`, t1.`name`, t1.`class`, t2.`name` as class_name, t1.`role`, t3.`name` as role_name, t1.`main`
					FROM `characters` t1
						INNER JOIN `classes` t2
						ON t2.`id` = t1.`class`
						INNER JOIN `roles` t3
						ON t3.`id` = t1.`role`
					WHERE t1.`user_id` = ? AND t1.`enabled` = TRUE ORDER BY `main` DESC");
	$stmt->bind_param("i", $_SESSION['user_id']);
	$stmt->execute();
	$user_characters = $stmt->get_result();
?>
