<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/rank_2.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['event_id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	} else {
		$event_id 	= $_POST['event_id'];
		$user_id 	= $_SESSION['user_id'];
		if (isset($_POST['note'])) {
			$note = $_POST['note'];
		} else {
			$note = "";
		}
	}
	
	// check for existing signup
	if (!$error) {
		$stmt->prepare("SELECT `id` FROM `event_signups` WHERE `event_id` = ? AND `user_id` = ?");
		$stmt->bind_param("ii", $event_id, $user_id);
		$stmt->execute();
		$result = $stmt->get_result();
		if (mysqli_num_rows($result) == 0) {
			// ERROR: no record found
			$error = true;
			$error_id = 111;
		} else {
			$row = mysqli_fetch_array($result);
			
			// record found, update existing			
			$stmt->prepare("UPDATE `event_signups` SET `note` = ? WHERE `id` = ?");
			$stmt->bind_param("si", $note, $row['id']);
			if(!($stmt->execute())) {
				// ERROR: failed to execute
				$error = true;
				$error_id = 109;
				echo "failed";
			}
		}
	}
	
	// log event
	if(!$error) {
		$stmt->prepare("SELECT `id`, `title`, `start` FROM `events` WHERE `id` = ?");
		$stmt->bind_param("i", $event_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$event_info = mysqli_fetch_array($result);
		
		$logDescription = "changed their signup note for <a href='/event.php?id=" . $event_info['id'] . "'>" . $event_info['title'] . " (" . $event_info['id'] . ")</a>: " . $note . ".";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 1)");
		$stmt->bind_param("is", $user_id, $logDescription);
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
