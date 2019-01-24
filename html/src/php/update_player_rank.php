<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_2.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['player_id']) || !isset($_POST['rank_id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// update user rank
	if (!$error) {
		$stmt->prepare("UPDATE `users` SET `rank` = ? WHERE `id` = ?");
		$stmt->bind_param("ii", $_POST['rank_id'], $_POST['player_id']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		}
	}
	
	// log event
	if(!$error) {
		$logDescription = "changed an account rank (ID " . $_POST['player_id'] . ").";
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
