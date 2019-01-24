<?php
	// get event signup details
	$stmt->prepare("SELECT 
					(SELECT COUNT(*) FROM `event_signups` WHERE `type` = 1 AND `event_id` = ?) as totalSignedUp, 
					(SELECT COUNT(*) FROM `event_signups` WHERE `type` = 2 AND `event_id` = ?) as totalCalledOut, 
					(SELECT COUNT(*) FROM `event_signups` WHERE `type` = 1 AND `event_id` = ? AND `character_role` = 1) as totalDPS, 
					(SELECT COUNT(*) FROM `event_signups` WHERE `type` = 1 AND `event_id` = ? AND `character_role` = 2) as totalHealers, 
					(SELECT COUNT(*) FROM `event_signups` WHERE `type` = 1 AND `event_id` = ? AND `character_role` = 3) as totalTanks, 
					(SELECT COUNT(*) FROM `event_signups` WHERE `type` = 1 AND `event_id` = ? AND `character_id` IN (SELECT `id` FROM `characters` WHERE `class` = 1)) as totalDruids, 
					(SELECT COUNT(*) FROM `event_signups` WHERE `type` = 1 AND `event_id` = ? AND `character_id` IN (SELECT `id` FROM `characters` WHERE `class` = 2)) as totalHunters, 
					(SELECT COUNT(*) FROM `event_signups` WHERE `type` = 1 AND `event_id` = ? AND `character_id` IN (SELECT `id` FROM `characters` WHERE `class` = 3)) as totalMages, 
					(SELECT COUNT(*) FROM `event_signups` WHERE `type` = 1 AND `event_id` = ? AND `character_id` IN (SELECT `id` FROM `characters` WHERE `class` = 4)) as totalPaladins, 
					(SELECT COUNT(*) FROM `event_signups` WHERE `type` = 1 AND `event_id` = ? AND `character_id` IN (SELECT `id` FROM `characters` WHERE `class` = 5)) as totalPriests, 
					(SELECT COUNT(*) FROM `event_signups` WHERE `type` = 1 AND `event_id` = ? AND `character_id` IN (SELECT `id` FROM `characters` WHERE `class` = 6)) as totalRogues, 
					(SELECT COUNT(*) FROM `event_signups` WHERE `type` = 1 AND `event_id` = ? AND `character_id` IN (SELECT `id` FROM `characters` WHERE `class` = 7)) as totalShamans, 
					(SELECT COUNT(*) FROM `event_signups` WHERE `type` = 1 AND `event_id` = ? AND `character_id` IN (SELECT `id` FROM `characters` WHERE `class` = 8)) as totalWarlocks, 
					(SELECT COUNT(*) FROM `event_signups` WHERE `type` = 1 AND `event_id` = ? AND `character_id` IN (SELECT `id` FROM `characters` WHERE `class` = 9)) as totalWarriors");
	$stmt->bind_param("iiiiiiiiiiiiii", $_GET['id'], $_GET['id'], $_GET['id'], $_GET['id'], $_GET['id'], $_GET['id'], $_GET['id'], $_GET['id'], $_GET['id'], $_GET['id'], $_GET['id'], $_GET['id'], $_GET['id'], $_GET['id']);
	$stmt->execute();
	$result = $stmt->get_result();
	$signup_details = mysqli_fetch_array($result);
?>
