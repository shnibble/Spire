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
	if (!isset($_POST['loot_date']) || !isset($_POST['loot_character']) || !isset($_POST['loot_item']) || !isset($_POST['loot_type'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// get optional variables
	if (!isset($_POST['loot_note']) || $_POST['loot_note'] == "") {
		$_POST['loot_note'] = null;
	}
	
	// get character info
	if (!$error) {
		$stmt->prepare("SELECT `name` FROM `characters` WHERE `id` = ?");
		$stmt->bind_param("i", $_POST['loot_character']);
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
		$stmt->prepare("SELECT `name`, `quality` FROM `items` WHERE `id` = ?");
		$stmt->bind_param("i", $_POST['loot_item']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		} else {
			$_item = mysqli_fetch_array($stmt->get_result());
		}
	}
	
	// convert time
	$date = new DateTime($_POST['loot_date'], $LOCAL_TIMEZONE);
	$date->setTimezone($SERVER_TIMEZONE);
	
	// add loot
	if (!$error) {
		$stmt->prepare("INSERT INTO `loot` (`timestamp`, `character_id`, `item_id`, `type`, `note`) VALUES (?, ?, ?, ?, ?)");
		if (!$stmt->bind_param("siiis", $date->format('Y-m-d H:i:s'), $_POST['loot_character'], $_POST['loot_item'], $_POST['loot_type'], $_POST['loot_note'])) {
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
		$logDescription = "added an item to the loot log: <a href='https://classicdb.ch/?item=" . $_POST['loot_item'] . "' target='_BLANK' class='quality-" . $_item['quality'] . "'>" . $_item['name'] . "</a> to " . $_character['name'] . ".";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 1)");
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
