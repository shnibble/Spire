<?php
	// get user pending loot logs
	$stmt->prepare("SELECT t1.`id`, t1.`user_id`, t1.`started`, 
					(SELECT COUNT(*) FROM `loot_log_items` WHERE `loot_log_id` = t1.`id`) as itemsCount
					FROM `loot_log` t1
					WHERE t1.`user_id` = ?");
	$stmt->bind_param("i", $_SESSION['user_id']);
	$stmt->execute();
	$user_loot_logs = $stmt->get_result();
?>
