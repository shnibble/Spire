<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_1.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['event_title']) || !isset($_POST['event_type']) || !isset($_POST['event_date'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// get checkbox values
	if (!$error) {
		if (empty($_POST['event_notify'])) {
			$event_notify = FALSE;
		} else {
			$event_notify = TRUE;
		}
		if (empty($_POST['event_log'])) {
			$event_log = FALSE;
		} else {
			$event_log = TRUE;
		}
	}
	
	// get optional variables
	if (!isset($_POST['event_leader']) || $_POST['event_leader'] == "") {
		$_POST['event_leader'] = null;
	}
	if (!isset($_POST['event_looter']) || $_POST['event_looter'] == "") {
		$_POST['event_looter'] = null;
	}
	if (!isset($_POST['event_buff']) || $_POST['event_buff'] == "") {
		$_POST['event_buff'] = null;
	}
	if (!isset($_POST['event_meetup']) || $_POST['event_meetup'] == "") {
		$_POST['event_meetup'] = null;
	}
	if (!isset($_POST['event_description']) || $_POST['event_description'] == "") {
		$_POST['event_description'] = null;
	}
	
	// convert time
	$date = new DateTime($_POST['event_date'], $LOCAL_TIMEZONE);
	$date->setTimezone($SERVER_TIMEZONE);
	
	// create event
	if (!$error) {
		$stmt->prepare("INSERT INTO `events` (`title`, `description`, `start`, `type`, `notify_late_signups`, `leader_id`, `looter_id`, `buff_instructions`, `meetup_instructions`, `log_attendance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("sssiiiissi", $_POST['event_title'], $_POST['event_description'], $date->format('Y-m-d H:i:s'), $_POST['event_type'], $event_notify, $_POST['event_leader'], $_POST['event_looter'], $_POST['event_buff'], $_POST['event_meetup'], $event_log);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		} else {
			$last_id = $conn->insert_id;
		}
	}
	
	// log event
	if(!$error) {
		$logDescription = "added an event <a href='/event.php?id=" . $last_id . "'>" . $_POST['event_title'] . " (" . $last_id . ")</a>.";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 0)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
		$stmt->execute();
	}
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		header("Location: /calendar.php");
		exit;
	} else {
		header("Location: /error.php?id=" . $error_id);
		exit;
	}
?>
