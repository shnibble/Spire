<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['character_id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// remove character from previous slot
	if (!$error) {
		$stmt->prepare("UPDATE `raid_template_slots` set `character_id` = NULL WHERE `character_id` = ?");
		if (!$stmt->bind_param("i", $_POST['character_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to insert into database
			$error = true;
			$error_id = 109;
		}
	}
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		echo 0;
	} else {
		echo $error_id;
	}
?>
