<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['timezone_id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// get new timezone info
	if (!$error) {
		$stmt->prepare("SELECT `name` FROM `timezones` WHERE `id` = ?");
		$stmt->bind_param("i", $_POST['timezone_id']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		} else {
			$_timezone = mysqli_fetch_array($stmt->get_result());
		}
	}
	
	// update user timezone
	if (!$error) {
		$stmt->prepare("UPDATE `users` SET `timezone_id` = ? WHERE `id` = ?");
		$stmt->bind_param("ii", $_POST['timezone_id'], $_SESSION['user_id']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		}
	}
	
	// log event
	if(!$error) {
		$logDescription = "changed their timezone to " . $_timezone['name'] . ".";
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
