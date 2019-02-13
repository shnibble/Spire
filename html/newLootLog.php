<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_1.php";
	
	$stmt->prepare("INSERT INTO `loot_log` (`user_id`) VALUES (?)");
	if (!$stmt->bind_param("i", $_SESSION['user_id'])) {
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
	
	// log event
	if(!$error) {
		$logDescription = "started a new loot log.";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 0)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
		$stmt->execute();
	}
	
	$stmt->close();
	$conn->close();
	
	header("Location: /lootLog.php?id=" . $last_id);
	exit;
?>
