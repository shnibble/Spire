<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_1.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['raid_template_id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// delete raid template slots
	if (!$error) {
		$stmt->prepare("DELETE FROM `raid_template_slots` WHERE `raid_template_id` = ?");
		if (!$stmt->bind_param("i", $_POST['raid_template_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to insert into database
			$error = true;
			$error_id = 109;
		}
	}
	
	// delete raid template
	if (!$error) {
		$stmt->prepare("DELETE FROM `raid_templates` WHERE `id` = ?");
		if (!$stmt->bind_param("i", $_POST['raid_template_id'])) {
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
