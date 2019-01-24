<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/rank_2.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['event_id']) || !isset($_POST['character_id']) || !isset($_POST['character_role'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	} else {
		$event_id 		= $_POST['event_id'];
		$character_id 	= $_POST['character_id'];
		$character_role = $_POST['character_role'];
		$user_id 		= $_SESSION['user_id'];
		if (isset($_POST['note'])) {
			$note = $_POST['note'];
		} else {
			$note = "";
		}
	}
	
	// check if event is in the future
	if (!$error) {
		$stmt->prepare("SELECT `start` FROM `events` WHERE `id` = ?");
		$stmt->bind_param("i", $event_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$event = mysqli_fetch_array($result);
		
		$now = new DateTime();
		$evd = new DateTime($event['start']);
		
		if ($now > $evd) {
			// ERROR: cannot sign up for past event
			$error = true;
			$error_id = 118;
		}
	}
	
	// check for existing signup
	if (!$error) {
		$stmt->prepare("SELECT `id`, `type` FROM `event_signups` WHERE `event_id` = ? AND `user_id` = ?");
		$stmt->bind_param("ii", $event_id, $user_id);
		$stmt->execute();
		$result = $stmt->get_result();
		if (mysqli_num_rows($result) == 0) {
			
			// no record, start a new one
			$stmt->prepare("INSERT INTO `event_signups` (`event_id`, `user_id`, `character_id`, `character_role`, `type`, `note`) VALUES (?, ?, ?, ?, 1, ?)");
			$stmt->bind_param("iiiis", $event_id, $user_id, $character_id, $character_role, $note);
			if(!($stmt->execute())) {
				// ERROR: failed to execute
				$error = true;
				$error_id = 109;
			}
		} else {
			$row = mysqli_fetch_array($result);
			
			// record found, update existing
			$stmt->prepare("UPDATE `event_signups` SET `character_id` = ?, `character_role` = ?, `type` = 1, `timestamp` = CURRENT_TIMESTAMP, `note` = ? WHERE `id` = ?");
			$stmt->bind_param("iisi", $character_id, $character_role, $note, $row['id']);
			if(!($stmt->execute())) {
				// ERROR: failed to execute
				$error = true;
				$error_id = 109;
			}
		}
	}
	
	// log event
	if(!$error) {
		$stmt->prepare("SELECT `id`, `title`, `start` FROM `events` WHERE `id` = ?");
		$stmt->bind_param("i", $event_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$event_info = mysqli_fetch_array($result);
		
		$logDescription = "signed up for <a href='/event.php?id=" . $event_info['id'] . "'>" . $event_info['title'] . " (" . $event_info['id'] . ")</a>.";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 1)");
		$stmt->bind_param("is", $user_id, $logDescription);
		$stmt->execute();
	}
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		
		header("Location: /calendar.php");
	} else {
		header("Location: /error.php?id=" . $error_id);
	}
?>
