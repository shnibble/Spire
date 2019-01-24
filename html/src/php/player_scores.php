<?php
	// get player attendance/loot score
	$stmt->prepare("SELECT attendanceScore,
							lootScore,
							attendanceScore - lootScore as totalScore
					FROM
					(
						SELECT 
						(
							SELECT SUM(t3.`value`)
							FROM `attendance` t1
							INNER JOIN `attendance_log` t2
								ON t2.`id` = t1.`attendance_log_id`
							INNER JOIN `attendance_values` t3
								ON t3.`event_type_id` = t2.`type` AND t3.`attendance_type_id` = t1.`type`
							WHERE t1.`user_id` = ?
						) as attendanceScore,
						(
							SELECT SUM(t2.`cost`) 
							FROM `loot` t1
							INNER JOIN `loot_types` t2 ON t2.`id` = t1.`type`
							WHERE t1.`enabled` = TRUE AND t1.`character_id` IN (SELECT `id` FROM `characters` WHERE `user_id` = ?)) as lootScore
					) t");
	$stmt->bind_param("ii", $_GET['id'], $_GET['id']);
	$stmt->execute();
	$player_scores = mysqli_fetch_array($stmt->get_result());
?>
