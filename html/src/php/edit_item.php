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
	if (!isset($_POST['item_id']) || !isset($_POST['item_quality']) || !isset($_POST['item_type'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// get optional variables
	if (!isset($_POST['item_priority']) || $_POST['item_priority'] == "") {
		$_POST['item_priority'] = null;
	}
	if (!isset($_POST['item_comment']) || $_POST['item_comment'] == "") {
		$_POST['item_comment'] = null;
	}
	
	// update item
	if (!$error) {
		$stmt->prepare("UPDATE `items` SET `quality` = ?, `default_type` = ?, `loot_priority` = ?, `comment` = ? WHERE `id` = ?");
		$stmt->bind_param("iissi", $_POST['item_quality'], $_POST['item_type'], $_POST['item_priority'], $_POST['item_comment'], $_POST['item_id']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		}
	}
	
	// log event
	if(!$error) {
		$logDescription = "updated an item from the items database (ID " . $_POST['item_id'] . ").";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 2)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
		$stmt->execute();
	}
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		header("Location: /items.php");
	} else {
		header("Location: /error.php?id=" . $error_id);
	}
?>
