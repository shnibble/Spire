<?php
	// get confirmed event raid rosters
	$stmt->prepare("SELECT t1.`id`, t2.`name` AS template_name 
					FROM `raid_rosters` t1 
						INNER JOIN `raid_roster_templates` t2 
							ON t2.`id` = t1.`raid_roster_template_id` 
					WHERE t1.`event_id` = ? AND t1.`confirmed` = TRUE");
	$stmt->bind_param("i", $_GET['id']);
	$stmt->execute();
	$event_raid_rosters_confirmed = $stmt->get_result();
?>
