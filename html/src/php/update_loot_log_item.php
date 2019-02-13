<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_1.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['id']) || !isset($_POST['type'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// get optional variables
	if (!isset($_POST['character_id']) || $_POST['character_id'] == "" || $_POST['character_id'] == 0) {
		$_POST['character_id'] = null;
	}
	if (!isset($_POST['item_id']) || $_POST['item_id'] == "" || $_POST['item_id'] == 0) {
		$_POST['item_id'] = null;
	}
	if (!isset($_POST['character_id']) || $_POST['character_id'] == "" || $_POST['character_id'] == 0) {
		$_POST['character_id'] = null;
	}
	if (!isset($_POST['note']) || $_POST['note'] == "") {
		$_POST['note'] = null;
	}
	
	
	// update item
	if (!$error) {
		$stmt->prepare("UPDATE `loot_log_items` set `character_id` = ?, `item_id` = ?, `type` = ?, `note` = ? WHERE `id` = ?");
		if (!$stmt->bind_param("iiisi", $_POST['character_id'], $_POST['item_id'], $_POST['type'], $_POST['note'], $_POST['id'])) {
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
