<?php
	// get lootlog
	$stmt->prepare("SELECT `id`, `started` FROM `loot_log` WHERE `user_id` = ? AND `id` = ?");
	$stmt->bind_param("ii", $_SESSION['user_id'], $_GET['id']);
	$stmt->execute();
	$result = $stmt->get_result();
	if (mysqli_num_rows($result) == 0) {
		$valid_id = false;
	} else {
		$valid_id = true;
		$loot_log = mysqli_fetch_array($result);
	}
?>
