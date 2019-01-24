<?php	
	// character specified
	if (isset($_GET['character']) && $_GET['character'] > 0) {
		$stmt->prepare("SELECT t1.`id`, t1.`timestamp`, t1.`character_id`, t2.`name` as characterName, t2.`user_id`, t3.`username`, t1.`item_id`, t4.`name` as itemName, t4.`quality`, t1.`type`, t5.`name` as typeName, t5.`cost`, t1.`note`
						FROM `loot` t1
							INNER JOIN `characters` t2
								ON t2.`id` = t1.`character_id`
							INNER JOIN `users` t3
								ON t3.`id` = t2.`user_id`
							INNER JOIN `items` t4
								ON t4.`id` = t1.`item_id`
							INNER JOIN `loot_types` t5
								ON t5.`id` = t1.`type`
						WHERE t1.`enabled` = TRUE AND t2.`id` = ? ORDER BY t1.`timestamp` DESC LIMIT ? OFFSET ?");
		$stmt->bind_param("iii", $_GET['character'], $results_per_page, $offset);
	}
	
	// user specified
	else if (isset($_GET['user']) && $_GET['user'] > 0) {
		$stmt->prepare("SELECT t1.`id`, t1.`timestamp`, t1.`character_id`, t2.`name` as characterName, t2.`user_id`, t3.`username`, t1.`item_id`, t4.`name` as itemName, t4.`quality`, t1.`type`, t5.`name` as typeName, t5.`cost`, t1.`note`
						FROM `loot` t1
							INNER JOIN `characters` t2
								ON t2.`id` = t1.`character_id`
							INNER JOIN `users` t3
								ON t3.`id` = t2.`user_id`
							INNER JOIN `items` t4
								ON t4.`id` = t1.`item_id`
							INNER JOIN `loot_types` t5
								ON t5.`id` = t1.`type`
						WHERE t1.`enabled` = TRUE AND t2.`user_id` = ? ORDER BY t1.`timestamp` DESC LIMIT ? OFFSET ?");
		$stmt->bind_param("iii", $_GET['user'], $results_per_page, $offset);
	} 
	
	// no user or character specified
	else {
		$stmt->prepare("SELECT t1.`id`, t1.`timestamp`, t1.`character_id`, t2.`name` as characterName, t2.`user_id`, t3.`username`, t1.`item_id`, t4.`name` as itemName, t4.`quality`, t1.`type`, t5.`name` as typeName, t5.`cost`, t1.`note`
						FROM `loot` t1
							INNER JOIN `characters` t2
								ON t2.`id` = t1.`character_id`
							INNER JOIN `users` t3
								ON t3.`id` = t2.`user_id`
							INNER JOIN `items` t4
								ON t4.`id` = t1.`item_id`
							INNER JOIN `loot_types` t5
								ON t5.`id` = t1.`type`
						WHERE t1.`enabled` = TRUE ORDER BY t1.`timestamp` DESC LIMIT ? OFFSET ?");
		$stmt->bind_param("ii", $results_per_page, $offset);
	}
	$stmt->execute();
	$loot = $stmt->get_result();
?>
