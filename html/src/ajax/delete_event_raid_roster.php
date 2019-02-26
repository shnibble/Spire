<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['raid_roster_id']) || !isset($_POST['event_id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}

	// get event info
    $stmt->prepare("SELECT `id`, `title`, `leader_id` FROM `events` WHERE `id` = ?");
	if (!$stmt->bind_param("i", $_POST['event_id'])) {
		// ERROR: failed to bind parameters
		$error = true;
		$error_id = 109;
	} else if (!$stmt->execute()) {
		// ERROR: failed to execute
		$error = true;
		$error_id = 109;
    } else {
        $result = $stmt->get_result();
    }
    if (mysqli_num_rows($result) != 1) { 
        // ERROR: invalid event id
        $error = true;
        $error_id = 119;
    } else {
		$event = mysqli_fetch_array($result);
	}

    // confirm user is event raid leader or admin
    if (($_SESSION['user_id'] != $event['leader_id']) && ($user['security'] < 2)) {
        // ERROR: invalid access
        $error = true;
        $error_id = 113;
    }
	
	// delete raid roster slots
	if (!$error) {
		$stmt->prepare("DELETE FROM `raid_roster_slots` WHERE `raid_roster_id` = ?");
		if (!$stmt->bind_param("i", $_POST['raid_roster_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		}
	}
	
	// delete raid roster
	if (!$error) {
		$stmt->prepare("DELETE FROM `raid_rosters` WHERE `id` = ?");
		if (!$stmt->bind_param("i", $_POST['raid_roster_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		}
	}
	
	// log event
	if(!$error) {
		$logDescription = "deleted a raid roster from event <a href='/event.php?id=" . $event['id'] . "'>" . $event['title'] . " (" . $event['id'] . ")</a>.";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 1)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
		$stmt->execute();
	}

	$stmt->close();
	$conn->close();
	
	if (!$error) {
		echo 0;
	} else {
		echo $error_id;
	}
?>
