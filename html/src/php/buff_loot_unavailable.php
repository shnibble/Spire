<?php
	// get unavailable buff loot
	$stmt->prepare("SELECT t1.`id`, t1.`timestamp`, t1.`character_id`, t2.`name` character_name, t1.`item_id`, t3.`name` item_name, t3.`quality`, t1.`note`, t1.`turned_in` 
					FROM `loot` t1
						INNER JOIN `characters` t2 
							ON t2.`id` = t1.`character_id`
						INNER JOIN `items` t3
							ON t3.`id` = t1.`item_id`
						INNER JOIN `users` t4 
							ON t4.`id` = t2.`user_id` 
					WHERE t1.`enabled` = TRUE AND t4.`active` = TRUE AND t1.`item_id` IN (SELECT `item_id` FROM `buff_items` WHERE `enabled` = TRUE) AND t1.`turned_in` IS NOT NULL ORDER BY t1.`timestamp`, t1.`turned_in` DESC");
	$stmt->execute();
	$buff_loot_unavailable = $stmt->get_result();
?>
