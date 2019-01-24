<?php
	// get user attendance/loot score
	$stmt->prepare("SELECT attendance_score,
							loot_score,
							attendance_score - loot_score as total_score
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
						) as attendance_score,
						(
							SELECT SUM(t2.`cost`) 
							FROM `loot` t1
							INNER JOIN `loot_types` t2 ON t2.`id` = t1.`type`
							WHERE t1.`enabled` = TRUE AND t1.`character_id` IN (SELECT `id` FROM `characters` WHERE `user_id` = ?)) as loot_score
					) t");
	$stmt->bind_param("ii", $_SESSION['user_id'], $_SESSION['user_id']);
	$stmt->execute();
	$user_scores = mysqli_fetch_array($stmt->get_result());
?>
