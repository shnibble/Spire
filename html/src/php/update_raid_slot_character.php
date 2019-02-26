<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['slot_id']) || !isset($_POST['template_id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// get optional variables
	if (!isset($_POST['character_id']) || $_POST['character_id'] == "" || $_POST['character_id'] == 0) {
		$_POST['character_id'] = null;
	}
	
	// remove character from previous slot
	if (!$error && !is_null($_POST['character_id'])) {
		$stmt->prepare("UPDATE `raid_template_slots` set `character_id` = NULL WHERE `raid_template_id` = ? AND `character_id` = ?");
		if (!$stmt->bind_param("ii", $_POST['template_id'], $_POST['character_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to insert into database
			$error = true;
			$error_id = 109;
		}
	}
	
	// remove existing characters from new slot
	if (!$error) {
		$stmt->prepare("UPDATE `raid_template_slots` set `character_id` = NULL WHERE `id` = ?");
		if (!$stmt->bind_param("i", $_POST['parameters'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to insert into database
			$error = true;
			$error_id = 109;
		}
	}
	
	// update slot character
	if (!$error) {
		$stmt->prepare("UPDATE `raid_template_slots` set `character_id` = ? WHERE `id` = ?");
		if (!$stmt->bind_param("ii", $_POST['character_id'], $_POST['slot_id'])) {
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
