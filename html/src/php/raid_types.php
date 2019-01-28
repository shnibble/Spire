<?php
	// get raid types
	$stmt->prepare("SELECT t1.`id`, t1.`name`, t1.`abbreviation`, ( (SELECT COUNT(*) FROM `raid_progression` WHERE `raid_type_id` = t1.`id` AND `completed` IS NOT NULL) / (SELECT COUNT(*) FROM `raid_progression` WHERE `raid_type_id` = t1.`id`) ) percent_complete FROM `raid_types` t1 ORDER BY t1.`id`");
	$stmt->execute();
	$raid_types = $stmt->get_result();
?>
