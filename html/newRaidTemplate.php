<?php
	if (!isset($_GET['template_id']) || $_GET['template_id'] == "" || !isset($_GET['event_id']) || $_GET['event_id'] == "") { 
		header("Location: /error.php?id=110");
		exit;
	}
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	
	$error = null;
	
	// get template info
	$stmt->prepare("SELECT * FROM `raid_template` WHERE `id` = ?");
	if (!$stmt->bind_param("i", $_GET['template_id'])) {
		// ERROR: failed to bind parameters
		$error = true;
		$error_id = 109;
	} else if (!$stmt->execute()) {
		// ERROR: failed to insert into database
		$error = true;
		$error_id = 109;
	} else {
		$template = mysqli_fetch_array($stmt->get_result());
	}
	
	// add event template
	if(!$error) {
		$stmt->prepare("INSERT INTO `raid_templates` (`raid_template_id`, `event_id`) VALUES (?, ?)");
		if (!$stmt->bind_param("ii", $_GET['template_id'], $_GET['event_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to insert into database
			$error = true;
			$error_id = 109;
		} else {
			$raid_template_id = $conn->insert_id;
		}
	}
	
	// add slots
	if(!$error) {
		$stmt->prepare("INSERT INTO `raid_template_slots` (`raid_template_id`, `slot_id`, `type_id`) VALUES (?, ?, ?)");
		$group = 1;
		$player = 1;
		$slot_name = "";
		
		while ($group <= 8 && !$error) {
			$player = 1;
			while ($player <= 5 && !$error) {
				$slot_name = "g" . $group . "_p" . $player;
				if (!$stmt->bind_param("isi", $raid_template_id, $slot_name, $template[$slot_name])) {
					// ERROR: failed to bind parameters
					$error = true;
					$error_id = 109;
				} else if (!$stmt->execute()) {
					// ERROR: failed to insert into database
					$error = true;
					$error_id = 109;
				}
				$player++;
			}
			$group++;
		}
	}
	
	// log event
	if(!$error) {
		$logDescription = "started a new raid template.";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 0)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
		$stmt->execute();
	}
	
	$stmt->close();
	$conn->close();
	
	header("Location: /eventRaidTemplate.php?id=" . $raid_template_id);
	exit;
?>
