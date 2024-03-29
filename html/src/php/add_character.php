<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['character_name']) || !isset($_POST['character_class']) || !isset($_POST['character_role'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// get main checkbox value
	if (!$error) {
		if (empty($_POST['character_main'])) {
			$main = FALSE;
		} else {
			$main = TRUE;
		}
	}
	
	// check if character name contains only characters
	if (!$error) {
		if (!preg_match('/^[a-zA-Z]+$/', $_POST['character_name'])) {
			// ERROR: invalid name format
			$error = true;
			$error_id = 116;
		}
	}
	
	// check if character name is unique
	if (!$error) {
		$stmt->prepare("SELECT `id` FROM `characters` WHERE `name` = ?");
		$stmt->bind_param("s", $_POST['character_name']);
		$stmt->execute();
		$result = $stmt->get_result();
		if (mysqli_num_rows($result) > 0) {
			// ERROR: not a unique character name
			$error = true;
			$error_id = 112;
		}
	}
	
	// if new main, remove old main flags
	if (!$error) {
		if ($main) {
			$stmt->prepare("UPDATE `characters` SET `main` = FALSE WHERE `user_id` = ?");
			if (!$stmt->bind_param("i", $_SESSION['user_id'])) {
				// ERROR: failed to bind parameters
				$error = true;
				$error_id = 109;
			} else if (!$stmt->execute()) {
				// ERROR: failed to insert into database
				$error = true;
				$error_id = 109;
			}
		}
	}
	
	// insert character into database
	if (!$error) {
		$stmt->prepare("INSERT INTO `characters` (`user_id`, `name`, `class`, `role`, `main`) VALUES (?, ?, ?, ?, ?)");
		if (!$stmt->bind_param("isiii", $_SESSION['user_id'], $_POST['character_name'], $_POST['character_class'], $_POST['character_role'], $main)) {
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
		$logDescription = "created a new character " . $_POST['character_name'] . ".";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 2)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
		$stmt->execute();
	}
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		header("Location: /profile.php");
		exit;
	} else {
		header("Location: /error.php?id=" . $error_id);
		exit;
	}
?>
