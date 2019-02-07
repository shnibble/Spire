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
	
	$now = null;
	$evd = null;
	// get event info
	if (!$error) {
		$stmt->prepare("SELECT `id`, `title`, `start`, `notify_late_signups` FROM `events` WHERE `id` = ?");
		$stmt->bind_param("i", $event_id);
		$stmt->execute();
		$result = $stmt->get_result();
		$event = mysqli_fetch_array($result);
		
		// check if event is in the future
		$now = new DateTime();
		$evd = new DateTime($event['start']);
		
		if ($now > $evd) {
			// ERROR: cannot sign up for past event
			$error = true;
			$error_id = 118;
		}
	}
	
	// check for existing signup
	if (!$error) {
		$stmt->prepare("SELECT `id`, `type` FROM `event_signups` WHERE `event_id` = ? AND `user_id` = ?");
		$stmt->bind_param("ii", $event_id, $user_id);
		$stmt->execute();
		$result = $stmt->get_result();
		if (mysqli_num_rows($result) == 0) {
			
			// no record, start a new one
			$stmt->prepare("INSERT INTO `event_signups` (`event_id`, `user_id`, `type`, `note`) VALUES (?, ?, 2, ?)");
			$stmt->bind_param("iis", $event_id, $user_id, $note);
			if(!($stmt->execute())) {
				// ERROR: failed to execute
				$error = true;
				$error_id = 109;
			}
		} else {
			$row = mysqli_fetch_array($result);
			
			// record found, update existing
			$stmt->prepare("UPDATE `event_signups` SET `type` = 2, `timestamp` = CURRENT_TIMESTAMP, `note` = ? WHERE `id` = ?");
			$stmt->bind_param("si", $note, $row['id']);
			if(!($stmt->execute())) {
				// ERROR: failed to execute
				$error = true;
				$error_id = 109;
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
		
		$logDescription = "called out for <a href='/event.php?id=" . $event_info['id'] . "'>" . $event_info['title'] . " (" . $event_info['id'] . ")</a>.";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 1)");
		$stmt->bind_param("is", $user_id, $logDescription);
		$stmt->execute();
	}
	
	// notify discord
	if (!$error && $event['notify_late_signups'] && ($now >= ($evd->modify('-24 hours')))) {
		$stmt->prepare("SELECT `username` FROM `users` WHERE `id` = ?");
		$stmt->bind_param("i", $_SESSION['user_id']);
		$stmt->execute();
		$res = mysqli_fetch_array($stmt->get_result());
		$username = $res['username'];
		$eventName = $event['title'];
		
		$curl = curl_init("https://discordapp.com/api/webhooks/542862212843438090/M2YDKq0CWCi840l8Oyi89Y7HDAcEuGUuoWcT-5IysztjIA62PKIlryKmCQ0SnTcHMota");
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode(array("content" => "$username __called out__ for $eventName ($event_id) Note: $note")));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_exec($curl);
	}
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		echo 0;
	} else {
		echo $error_id;
	}
?>
