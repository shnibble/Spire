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
	if (!isset($_POST['event_title']) || !isset($_POST['event_type']) || !isset($_POST['event_date'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// convert time
	$date = new DateTime($_POST['event_date'], $LOCAL_TIMEZONE);
	$date->setTimezone($SERVER_TIMEZONE);
	
	// create attendance log and link to event
	if (!$error) {
		// create attendance log
		$stmt->prepare("INSERT INTO `attendance_log` (`title`, `type`, `date`, `user_id`) VALUES (?, ?, ?, ?)");
		$stmt->bind_param("sisi", $_POST['event_title'], $_POST['event_type'], $date->format('Y-m-d H:i:s'), $_SESSION['user_id']);
		$stmt->execute();
		$last_id = $conn->insert_id;
		
		// if linking to event, update event with attendance log id
		if (isset($_POST['event_id']) && $_POST['event_id'] > 0) {
			$stmt->prepare("UPDATE `events` SET `attendance_log_id` = ? WHERE `id` = ?");
			$stmt->bind_param("ii", $last_id, $_POST['event_id']);
			$stmt->execute();
		}
	}
	
	// iterate through attendance records
	if (!$error) {
		$stmt->prepare("INSERT INTO `attendance` (`attendance_log_id`, `user_id`, `type`) VALUES (?, ?, ?)");
		for ($i = 0; $i < count($_POST['user_id']); $i++) {
			$stmt->bind_param("iii", $last_id, $_POST['user_id'][$i], $_POST['attnd'][$i]);
			$stmt->execute();
			
		}
	}
	
	// log event
	if(!$error) {
		if (isset($_POST['event_id']) && $_POST['event_id'] > 0) {
			$logDescription = "logged attendance for <a href='/event.php?id=" . $_POST['event_id'] . "'>" . $_POST['event_title'] . " (" . $_POST['event_id'] . ")</a>.";
		} else {
			$logDescription = "logged attendance for " . $_POST['event_title'] . " (unscheduled).";
		}
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 2)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
		$stmt->execute();
	}
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		
		header("Location: /admin/adminattendance.php");
		exit;
	} else {
		header("Location: /error.php?id=" . $error_id);
		exit;
	}
?>
