<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_3.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['player_id']) || !isset($_POST['security_id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// get current player info
	if (!$error) {
		$stmt->prepare("SELECT `username`, `security` FROM `users` WHERE `id` = ?");
		$stmt->bind_param("i", $_POST['player_id']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		} else {
			$_player = mysqli_fetch_array($stmt->get_result());
		}
	}
	
	// get new security info
	if (!$error) {
		$stmt->prepare("SELECT `name` FROM `security` WHERE `id` = ?");
		$stmt->bind_param("i", $_POST['security_id']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		} else {
			$_security = mysqli_fetch_array($stmt->get_result());
		}
	}
	
	// update player security
	if (!$error) {
		$stmt->prepare("UPDATE `users` SET `security` = ? WHERE `id` = ?");
		$stmt->bind_param("ii", $_POST['security_id'], $_POST['player_id']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		}
	}
	
	// log event
	if(!$error) {
		if ($_player['security'] < $_POST['security_id']) {
			$logDescription = "increased " . $_player['username'] . "'s security level to " . $_security['name'] . ".";
		} else {
			$logDescription = "decreased " . $_player['username'] . "'s security level to " . $_security['name'] . ".";			
		}
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 2)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
		$stmt->execute();
	}
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		echo 0;
	} else {
		echo $error_id;
	}
?>
