<?php
	// get event raid templates
	$stmt->prepare("SELECT t1.`id`, t2.`name` as template_name 
					FROM `raid_templates` t1 
						INNER JOIN `raid_template` t2 
							ON t2.`id` = t1.`raid_template_id` 
					WHERE t1.`event_id` = ?");
	$stmt->bind_param("i", $_GET['id']);
	$stmt->execute();
	$event_raid_templates = $stmt->get_result();
?>
