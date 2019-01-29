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
	if (!isset($_POST['occasion_type']) || !isset($_POST['occasion_date'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// get occasion info
	if (!$error) {
		$stmt->prepare("SELECT `name` FROM `occasion_types` WHERE `id` = ?");
		$stmt->bind_param("i", $_POST['occasion_type']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		} else {
			$_occasion= mysqli_fetch_array($stmt->get_result());
		}
	}
	
	// convert time
	$date = new DateTime($_POST['occasion_date'], $LOCAL_TIMEZONE);
	$date->setTimezone($SERVER_TIMEZONE);
	
	// add occasion
	if (!$error) {
		$stmt->prepare("INSERT INTO `occasions` (`type`, `date`) VALUES (?, ?)");
		if (!$stmt->bind_param("is", $_POST['occasion_type'], $date->format('Y-m-d H:i:s'))) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to insert into database
			$error = true;
			$error_id = 109;
		} else {
			$last_id = $conn->insert_id;
		}
	}
	
	// log event
	if(!$error) {
		$logDescription = "added a server occasion: " . $_occasion['name'] . " (ID " . $last_id . ").";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 2)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
		$stmt->execute();
	}
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		header("Location: /admin/adminoccasions.php");
	} else {
		header("Location: /error.php?id=" . $error_id);
	}
?>
