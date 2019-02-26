<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['character_id']) || !isset($_POST['raid_roster_id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// remove character from previous slot
	if (!$error) {
		$stmt->prepare("UPDATE `raid_roster_slots` set `character_id` = NULL WHERE `character_id` = ? AND `raid_roster_id` = ?");
		if (!$stmt->bind_param("ii", $_POST['character_id'], $_POST['raid_roster_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to execute
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
