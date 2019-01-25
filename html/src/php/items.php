<?php
	// get items
	$stmt->prepare("SELECT t1.`id`, t1.`name`, t1.`quality`, t1.`default_type`, t2.`name` default_type_name, t1.`loot_priority`, t1.`comment` 
					FROM `items` t1 
						INNER JOIN `loot_types` t2 
							ON t2.`id` = t1.`default_type` 
					WHERE t1.`enabled` = TRUE ORDER BY t1.`name`");
	$stmt->execute();
	$items = $stmt->get_result();
?>
