<?php
	// get badges
	$stmt->prepare("SELECT `id`, `name`, `description` FROM `badges` WHERE `enabled` = TRUE");
	$stmt->execute();
	$result = $stmt->get_result();
	$badges_array = [];
	while ($row = mysqli_fetch_array($result)) {
		$badges_array[$row['id']] = $row;
	}
?>
