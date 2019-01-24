<?php
	// get user loot
	$stmt->prepare("SELECT t1.`id`, t1.`timestamp`, t1.`character_id`, t4.`name` as character_name, t1.`item_id`, t2.`name`, t2.`quality`, t1.`type`, t3.`name` as type_name, t3.`cost`, t1.`note`
					FROM `loot` t1
						INNER JOIN `items` t2
							ON t2.`id` = t1.`item_id`
						INNER JOIN `loot_types` t3
							ON t3.`id` = t1.`type`
						INNER JOIN `characters` t4
							ON t4.`id` = t1.`character_id`
					WHERE t1.`enabled` = TRUE AND t1.`character_id` IN (SELECT `id` FROM `characters` WHERE `user_id` = ?) ORDER BY t1.`timestamp` DESC");
	$stmt->bind_param("i", $_SESSION['user_id']);
	$stmt->execute();
	$user_loot = $stmt->get_result();
?>
