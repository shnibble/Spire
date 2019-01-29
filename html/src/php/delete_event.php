<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_2.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['event_id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// get event info
	if (!$error) {
		$stmt->prepare("SELECT `title` FROM `events` WHERE `id` = ?");
		$stmt->bind_param("i", $_POST['event_id']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		} else {
			$_event= mysqli_fetch_array($stmt->get_result());
		}
	}
	
	// delete event
	if (!$error) {
		$stmt->prepare("UPDATE `events` SET `enabled` = FALSE WHERE `id` = ?");
		if (!$stmt->bind_param("i", $_POST['event_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		}
	}
	
	// log event
	if(!$error) {
		$logDescription = "deleted an event: " . $_event['title'] . " (ID " . $_POST['event_id'] . ").";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 2)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
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
