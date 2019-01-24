<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_2.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['new_pass_1']) || !isset($_POST['new_pass_2']) || !isset($_POST['player_id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// check if passwords match
	if (!$error) {
		if ($_POST['new_pass_1'] != $_POST['new_pass_2']) {
			// ERROR: passwords do not match
			$error = true;
			$error_id = 104;
		} else {
			// hash password
			$password_hashed = password_hash($_POST['new_pass_1'], PASSWORD_DEFAULT);				
		}
	}
	
	// update player password
	if (!$error) {
		$stmt->prepare("UPDATE `users` SET `password` = ? WHERE `id` = ?");
		$stmt->bind_param("si", $password_hashed, $_POST['player_id']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		}
	}
	
	// log event
	if(!$error) {
		$logDescription = "changed an account password (ID " . $_POST['player_id'] . ").";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 2)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
		$stmt->execute();
	}
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		header("Location: /admin/viewUser.php?id=" . $_POST['player_id']);
	} else {
		header("Location: /error.php?id=" . $error_id);
	}
?>
