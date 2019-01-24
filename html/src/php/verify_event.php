<?php
	// verify event
	$stmt->prepare("SELECT `id`, `title`, `type`, `start` FROM `events` WHERE `id` = ?");
	$stmt->bind_param("i", $_POST['event']);
	$stmt->execute();
	$vEvent = $stmt->get_result();
	
	if (mysqli_num_rows($vEvent) == 1) {
		$valid_event = true;
		$vEvent = mysqli_fetch_array($vEvent);
	} else {
		$valid_event = false;
	}
?>
