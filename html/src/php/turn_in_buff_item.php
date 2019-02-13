<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_1.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['loot_id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// get character info
	if (!$error) {
		$stmt->prepare("SELECT `name` FROM `characters` WHERE `id` = (SELECT `character_id` FROM `loot` WHERE `id` = ?)");
		$stmt->bind_param("i", $_POST['loot_id']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		} else {
			$_character = mysqli_fetch_array($stmt->get_result());
		}
	}
	
	// get item info
	if (!$error) {
		$stmt->prepare("SELECT `id`, `name`, `quality` FROM `items` WHERE `id` = (SELECT `item_id` FROM `loot` WHERE `id` = ?)");
		$stmt->bind_param("i", $_POST['loot_id']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		} else {
			$_item = mysqli_fetch_array($stmt->get_result());
		}
	}
	
	// update loot
	if (!$error) {
		$stmt->prepare("UPDATE `loot` SET `turned_in` = NOW() WHERE `id` = ?");
		if (!$stmt->bind_param("i", $_POST['loot_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		}
	}
	
	// log event
	if(!$error) {
		$logDescription = "turned in " . $_character['name'] . "'s <a href='https://classicdb.ch/?item=" . $_item['id'] . "' target='_BLANK' class='quality-" . $_item['quality'] . "'>" . $_item['name'] . "</a>.";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 0)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
		$stmt->execute();
	}
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		header("Location: /buffs.php");
		exit;
	} else {
		header("Location: /error.php?id=" . $error_id);
		exit;
	}
?>
