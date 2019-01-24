<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['character_id']) || !isset($_POST['character_name']) || !isset($_POST['character_role'])) {
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
		$stmt->prepare("UPDATE `characters` set `role` = ?, `main` = ? WHERE `id` = ?");
		if (!$stmt->bind_param("iii", $_POST['character_role'], $main, $_POST['character_id'])) {
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
		$logDescription = "updated their character " . $_POST['character_name'] . ".";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 2)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
		$stmt->execute();
	}
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		header("Location: /profile.php");
	} else {
		header("Location: /error.php?id=" . $error_id);
	}
?>
