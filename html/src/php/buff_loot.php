<?php
	// get buff loot
	$stmt->prepare("SELECT t1.`id`, t1.`timestamp`, t1.`character_id`, t2.`name` character_name, t1.`item_id`, t3.`name` item_name, t3.`quality`, t1.`note`, t1.`turned_in` 
					FROM `loot` t1
						INNER JOIN `characters` t2 
							ON t2.`id` = t1.`character_id`
						INNER JOIN `items` t3
							ON t3.`id` = t1.`item_id`
						INNER JOIN `users` t4 
							ON t4.`id` = t2.`user_id` 
					WHERE t4.`active` = TRUE AND t1.`item_id` IN (SELECT `item_id` FROM `buff_items` WHERE `enabled` = TRUE) ORDER BY t1.`turned_in`, t1.`timestamp`");
	$stmt->execute();
	$buff_loot = $stmt->get_result();
?>
