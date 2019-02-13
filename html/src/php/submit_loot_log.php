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
	if (!isset($_POST['lootlog_id']) || !isset($_POST['loot_date'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// convert time
	$date = new DateTime($_POST['loot_date'], $LOCAL_TIMEZONE);
	$date->setTimezone($SERVER_TIMEZONE);
		
	
	// clean incomplete items
	if (!$error) {
		// prepare statement
		$stmt->prepare("DELETE FROM `loot_log_items` WHERE `loot_log_id` = ? AND (`character_id` IS NULL OR `item_id` IS NULL)");
		if (!$stmt->bind_param("i", $_POST['lootlog_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to insert into database
			$error = true;
			$error_id = 109;
		}
	}
	
	// add loot
	if (!$error) {
		// prepare statement
		$stmt->prepare("INSERT INTO `loot` (`timestamp`, `character_id`, `item_id`, `type`, `note`) SELECT ?, `character_id`, `item_id`, `type`, `note` FROM `loot_log_items` WHERE `loot_log_id` = ?");
		if (!$stmt->bind_param("si", $date->format('Y-m-d H:i:s'), $_POST['lootlog_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to insert into database
			$error = true;
			$error_id = 109;
		}
	}
	
	// delete loot log items
	if (!$error) {
		$stmt->prepare("DELETE FROM `loot_log_items` WHERE `loot_log_id` = ?");
		if (!$stmt->bind_param("i", $_POST['lootlog_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to insert into database
			$error = true;
			$error_id = 109;
		}
	}
	
	// delete loot log
	if (!$error) {
		$stmt->prepare("DELETE FROM `loot_log` WHERE `id` = ?");
		if (!$stmt->bind_param("i", $_POST['lootlog_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to insert into database
			$error = true;
			$error_id = 109;
		}
	}
	
	// log event
	if(!$error) {
		$logDescription = "submitted a loot log.";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 0)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
		$stmt->execute();
	}
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		header("Location: /loot.php");
		exit;
	} else {
		header("Location: /error.php?id=" . $error_id);
		exit;
	}
?>
