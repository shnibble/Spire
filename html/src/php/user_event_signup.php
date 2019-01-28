<?php
	// get user's event signup
	$stmt->prepare("SELECT `type`, `character_id`, `character_role`, `note` FROM `event_signups` WHERE `event_id` = ? AND `user_id` = ?");
	$stmt->bind_param("ii", $_GET['id'], $_SESSION['user_id']);
	$stmt->execute();
	$user_event_signup = mysqli_fetch_array($stmt->get_result());
	?>
