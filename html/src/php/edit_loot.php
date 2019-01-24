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
	if (!isset($_POST['loot_id']) || !isset($_POST['loot_character']) || !isset($_POST['loot_item']) || !isset($_POST['loot_type'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// update loot
	if (!$error) {
		$stmt->prepare("UPDATE `loot` SET `character_id` = ?, `item_id` = ?, `type` = ? WHERE `id` = ?");
		$stmt->bind_param("iiii", $_POST['loot_character'], $_POST['loot_item'], $_POST['loot_type'], $_POST['loot_id']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		}
	}
	
	// log event
	if(!$error) {
		$logDescription = "updated a loot item (ID " . $_POST['loot_id'] . ").";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 2)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
		$stmt->execute();
	}
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		header("Location: /loot.php");
	} else {
		header("Location: /error.php?id=" . $error_id);
	}
?>
