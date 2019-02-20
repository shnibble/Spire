<?php
	// get raid template
	$valid_id = true;
	$stmt->prepare("SELECT t1.`id`, t1.`raid_template_id`, t1.`event_id`, t2.`name` as `template_name` 
					FROM `raid_templates` t1 
						INNER JOIN `raid_template` t2
							ON t2.`id` = t1.`raid_template_id` 
					WHERE t1.`id` = ?");
	$stmt->bind_param("i", $_GET['id']);
	$stmt->execute();
	$result = $stmt->get_result();
	if (mysqli_num_rows($result) <= 0) {
		$valid_id = false;
	}
	$raid_template = mysqli_fetch_array($result);
	
	// get raid template slots
	$stmt->prepare("SELECT t1.`id`, t1.`slot_id`, t1.`type_id`, t2.`description` as type_description, t1.`character_id`, t3.`name` as character_name, t3.`class` as character_class, t4.`name` as character_class_name, t5.`rank` 
					FROM `raid_template_slots` t1 
						INNER JOIN `raid_template_slot_types` t2 
							ON t2.`id` = t1.`type_id` 
						LEFT JOIN `characters` t3 
							ON t3.`id` = t1.`character_id` 
						LEFT JOIN `classes` t4 
							ON t4.`id` = t3.`class` 
						LEFT JOIN `users` t5 
							ON t5.`id` = t3.`user_id` 
					WHERE t1.`raid_template_id` = ? ORDER BY `slot_id` ASC");
	$stmt->bind_param("i", $_GET['id']);
	$stmt->execute();
	$raid_template_slots = $stmt->get_result();
	
	// get event
	$stmt->prepare("SELECT * FROM `events` WHERE `id` = (SELECT `event_id` FROM `raid_templates` WHERE `id` = ?)");
	$stmt->bind_param("i", $_GET['id']);
	$stmt->execute();
	$event = mysqli_fetch_array($stmt->get_result());
?>
