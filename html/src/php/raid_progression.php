<?php
	// get raid progression
	$stmt->prepare("SELECT `id`, `raid_type_id`, `boss_name`, `boss_order`, `completed` FROM `raid_progression` ORDER BY `raid_type_id`, `boss_order`");
	$stmt->execute();
	$raid_progression = $stmt->get_result();
?>
