<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_2.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['admin_pass']) || !isset($_POST['new_pass_1']) || !isset($_POST['new_pass_2']) || !isset($_POST['player_id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// get player's information
	// prevent users from changing passwords for other users with an equal or higher security level
	if (!$error) {
		$stmt->prepare("SELECT `username`, `security` FROM `users` WHERE `id` = ?");
		$stmt->bind_param("s", $_POST['player_id']);
		$stmt->execute();
		$result = mysqli_fetch_array($stmt->get_result());
		$player_security = $result['security'];
		$player_name = $result['username'];
		
		if ($player_security >= $user['security']) {
			// ERROR: player's security is equal or higher to yours
			$error = true;
			$error_id = 124;
		}
	}
	
	// verify admin password
	if (!$error) {
		$stmt->prepare("SELECT `password` FROM `users` WHERE `id` = ?");
		$stmt->bind_param("s", $_SESSION['user_id']);
		$stmt->execute();
		$result = $stmt->get_result();
		if (mysqli_num_rows($result) == 0) {
			// ERROR: username not found
			$error = true;
			$error_id = 107;
		} else {
			$result = mysqli_fetch_array($result);
			$admin_pass = $result['password'];
			if (!password_verify($_POST['admin_pass'], $admin_pass)) {
				// ERROR: incorrect password
				$error = true;
				$error_id = 123;
			}
		}
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
		$logDescription = "force changed " . $player_name . "'s password.";
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
