<?php
	// get player attendance/loot score
	$stmt->prepare("SELECT `loot_cost`, `attendance_score`, (`attendance_score` - `loot_cost`) spread, `30_day_attendance_rate` FROM `user_scores` WHERE `user_id` = ?");
	$stmt->bind_param("i", $_GET['id']);
	$stmt->execute();
	$player_scores = mysqli_fetch_array($stmt->get_result());
?>
