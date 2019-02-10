<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_2.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	
	$error = false;	
	$dates = array();
	$types = array();
	$total = 0;
	
	// filter POST variables into arrays
	foreach($_POST as $key => $vals) {
	  
		if ($key == "occasion_date") {
			foreach($vals as $val) {
				if ($val != "") {
					$dates[] = $val;
				}
			}
		}
	  
		if ($key == "occasion_type") {
			foreach($vals as $val) {
				if ($val != "") {
					$types[] = $val;
				}
			}
		}
	}
	
	// limit max to shorter array (in case one variable is passed but not the other)
	if (sizeof($dates) > sizeof($types)) {
		$total = sizeof($types);
	} else {
		$total = sizeof($dates);
	}
	
	for ($i = 0; $i < $total; $i++) {
		
		// convert time
		$date = new DateTime($dates[$i], $LOCAL_TIMEZONE);
		$date->setTimezone($SERVER_TIMEZONE);
		
		// add occasion
		if (!$error) {
			$stmt->prepare("INSERT INTO `occasions` (`type`, `date`) VALUES (?, ?)");
			if (!$stmt->bind_param("is", $types[$i], $date->format('Y-m-d H:i:s'))) {
				// ERROR: failed to bind parameters
				$error = true;
				$error_id = 109;
			} else if (!$stmt->execute()) {
				// ERROR: failed to execute
				$error = true;
				$error_id = 109;
			} else {
				$last_id = $conn->insert_id;
			}
		}
		
		// log event
		if(!$error) {
			$logDescription = "added a server occasion (ID " . $last_id . ").";			
			$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 2)");
			$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
			$stmt->execute();
		}
	}	
	
	
	$stmt->close();
	$conn->close();
	
	if (!$error) {
		header("Location: /admin/adminoccasions.php");
		exit;
	} else {
		header("Location: /error.php?id=" . $error_id);
		exit;
	}
?>
