<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_2.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['announcement_title']) || !isset($_POST['announcement_content'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// get optional POST variables
	if (!$error) {
		if (isset($_POST['bridge'])) {
			$bridge = true;
		} else {
			$bridge = false;
		}
	}
	
	// validate string lengths
	if (!$error) {
		if (strlen($_POST['announcement_title'] > 100) || strlen($_POST['announcement_content'] > 890)) {
			// ERROR: string too long
			$error = true;
			$error_id = 121;
		}
	}
	
	// add announcement
	if (!$error) {
		$stmt->prepare("INSERT INTO `announcements` (`title`, `content`, `user_id`) VALUES (?, ?, ?)");
		if (!$stmt->bind_param("ssi", $_POST['announcement_title'], $_POST['announcement_content'], $_SESSION['user_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed execute
			$error = true;
			$error_id = 109;
		} else {
			$last_id = $conn->insert_id;
		}
	}
	
	// bridge to discord
	if (!$error && $bridge) {
		$title = $_POST['announcement_title'];
		$content = $_POST['announcement_content'];
		$curl = curl_init("https://discordapp.com/api/webhooks/536954368449052672/r6RjD1WPSia7OV2SXJloVcy1cz0f4SWzrY5Br0IEmzb20xhYnnq7wZEWlbY6irlzKMAQ");
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array("content" => "@everyone **$title** \n$content")));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_exec($curl);
	}
	
	// log event
	if(!$error) {
		$logDescription = "posted an announcement: " . $_POST['announcement_title'] . ".";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 1)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
		$stmt->execute();
	}
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		header("Location: /home.php");
		exit;
	} else {
		header("Location: /error.php?id=" . $error_id);
		exit;
	}
?>
