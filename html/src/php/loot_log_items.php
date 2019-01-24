<?php
	// get lootlog items
	$stmt->prepare("SELECT t1.`id`, t1.`character_id`, t2.`name` as characterName, t1.`item_id`, t3.`name` as itemName, t3.`quality`, t3.`loot_priority`, t3.`comment`, t1.`type`, t4.`name` as typeName, t1.`note`
					FROM `loot_log_items` t1
						LEFT JOIN `characters` t2
						ON t2.`id` = t1.`character_id`
						LEFT JOIN `items` t3
						ON t3.`id` = t1.`item_id`
						LEFT JOIN `loot_types` t4
						ON t4.`id` = t1.`type`
					WHERE t1.`loot_log_id` = ? ORDER BY t1.`id`");
	$stmt->bind_param("i", $_GET['id']);
	$stmt->execute();
	$loot_log_items = $stmt->get_result();
?>
