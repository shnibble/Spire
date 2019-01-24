<?php	
	// character specified
	if (isset($_GET['character']) && $_GET['character'] > 0) {
		$stmt->prepare("SELECT t1.`id` FROM `loot` t1
							INNER JOIN `characters` t2
								ON t2.`id` = t1.`character_id`
						WHERE t1.`enabled` = TRUE AND t2.`id` = ?");
		$stmt->bind_param("i", $_GET['character']);
	}
	
	// user specified
	else if (isset($_GET['user']) && $_GET['user'] > 0) {
		$stmt->prepare("SELECT t1.`id` FROM `loot` t1
							INNER JOIN `characters` t2
								ON t2.`id` = t1.`character_id`
						WHERE t1.`enabled` = TRUE AND t2.`user_id` = ?");
		$stmt->bind_param("i", $_GET['user']);
	} 
	
	// no user or character specified
	else {
		$stmt->prepare("SELECT t1.`id` FROM `loot` t1 WHERE t1.`enabled` = TRUE");
	}
	$stmt->execute();
	$loot_count = $stmt->get_result();
?>
