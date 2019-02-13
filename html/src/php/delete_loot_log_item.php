<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_1.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// delete item
	if (!$error) {
		$stmt->prepare("DELETE FROM `loot_log_items` WHERE `id` = ?");
		if (!$stmt->bind_param("i", $_POST['id'])) {
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
		echo "success";
	} else {
		echo $error_id;
	}
?>
