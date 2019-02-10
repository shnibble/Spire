<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_2.php";
	
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
	
	$stmt->close();
	$conn->close();
	
	header("Location: /lootLog.php?id=" . $last_id);
	exit;
?>
