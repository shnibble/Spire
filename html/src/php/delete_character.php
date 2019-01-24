<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['character_id']) || !isset($_POST['character_name'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// check if main character
	if (!$error) {
		$stmt->prepare("SELECT `main` FROM `characters` WHERE `id` = ?");
		$stmt->bind_param("i", $_POST['character_id']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		} else {
			$result = mysqli_fetch_array($stmt->get_result());
			if ($result['main'] != 0) {
				// ERROR: cannot delete main character
				$error = true;
				$error_id = 120;
			}
		}
	}	
	
	// delete character
	if (!$error) {
		$newName = "DELETED_" . $_POST['character_name'];
		$stmt->prepare("UPDATE `characters` SET `enabled` = FALSE, `name` = ? WHERE `id` = ?");
		$stmt->bind_param("si", $newName, $_POST['character_id']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		}
	}
	
	// log event
	if(!$error) {
		$logDescription = "deleted their character " . $_POST['character_name'] . " (ID " . $_POST['character_id'] . ").";
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
