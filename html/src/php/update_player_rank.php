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
	
	// get current player info
	if (!$error) {
		$stmt->prepare("SELECT `username`, `rank` FROM `users` WHERE `id` = ?");
		$stmt->bind_param("i", $_POST['player_id']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		} else {
			$_player = mysqli_fetch_array($stmt->get_result());
		}
	}
	
	// get new rank info
	if (!$error) {
		$stmt->prepare("SELECT `name` FROM `ranks` WHERE `id` = ?");
		$stmt->bind_param("i", $_POST['rank_id']);
		if(!($stmt->execute())) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		} else {
			$_rank = mysqli_fetch_array($stmt->get_result());
		}
	}
	
	// update player rank
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
		if ($_player['rank'] < $_POST['rank_id']) {
			$logDescription = "promoted " . $_player['username'] . " to " . $_rank['name'] . ".";
		} else {
			$logDescription = "demoted " . $_player['username'] . " to " . $_rank['name'] . ".";			
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
