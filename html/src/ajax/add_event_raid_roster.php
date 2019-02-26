<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	
    $error = null;
    
	// get POST variables
	if (!isset($_POST['raid_roster_template_id']) || !isset($_POST['event_id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
    }
    
    // get event info
    $stmt->prepare("SELECT `id`, `title`, `leader_id` FROM `events` WHERE `id` = ?");
	if (!$stmt->bind_param("i", $_POST['event_id'])) {
		// ERROR: failed to bind parameters
		$error = true;
		$error_id = 109;
	} else if (!$stmt->execute()) {
		// ERROR: failed to execute
		$error = true;
		$error_id = 109;
    } else {
        $result = $stmt->get_result();
    }
    if (mysqli_num_rows($result) != 1) { 
        // ERROR: invalid event id
        $error = true;
        $error_id = 119;
    } else {
		$event = mysqli_fetch_array($result);
	}

    // confirm user is event raid leader or admin
    if (($_SESSION['user_id'] != $event['leader_id']) && ($user['security'] < 2)) {
        // ERROR: invalid access
        $error = true;
        $error_id = 113;
    }

	// get template info
	$stmt->prepare("SELECT * FROM `raid_roster_templates` WHERE `id` = ?");
	if (!$stmt->bind_param("i", $_POST['raid_roster_template_id'])) {
		// ERROR: failed to bind parameters
		$error = true;
		$error_id = 109;
	} else if (!$stmt->execute()) {
		// ERROR: failed to execute
		$error = true;
		$error_id = 109;
	} else {
		$template = mysqli_fetch_array($stmt->get_result());
	}
	
	// add event raid roster
	if(!$error) {
		$stmt->prepare("INSERT INTO `raid_rosters` (`raid_roster_template_id`, `event_id`, `confirmed`) VALUES (?, ?, FALSE)");
		if (!$stmt->bind_param("ii", $_POST['raid_roster_template_id'], $_POST['event_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to execute
			$error = true;
			$error_id = 109;
		} else {
			$raid_roster_id = $conn->insert_id;
		}
	}
	
	// add slots
	if(!$error) {
		$stmt->prepare("INSERT INTO `raid_roster_slots` (`raid_roster_id`, `slot_id`, `type_id`) VALUES (?, ?, ?)");
		$group = 1;
		$player = 1;
		$slot_name = "";
		
		while ($group <= 8 && !$error) {
			$player = 1;
			while ($player <= 5 && !$error) {
				$slot_name = "g" . $group . "_p" . $player;
				if (!$stmt->bind_param("isi", $raid_roster_id, $slot_name, $template[$slot_name])) {
					// ERROR: failed to bind parameters
					$error = true;
					$error_id = 109;
				} else if (!$stmt->execute()) {
					// ERROR: failed to execute
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
		$logDescription = "added a raid roster to event <a href='/event.php?id=" . $event['id'] . "'>" . $event['title'] . " (" . $event['id'] . ")</a>.";
		$stmt->prepare("INSERT INTO `log` (`user_id`, `description`, `security_level`) VALUES (?, ?, 1)");
		$stmt->bind_param("is", $_SESSION['user_id'], $logDescription);
		$stmt->execute();
	}
	
	$stmt->close();
    $conn->close();
    
    if (!$error) { ?>
        <a href="/eventRaidRoster.php?id=<?php echo $raid_roster_id; ?>" class="raid-roster-link"><?php echo $template['name']; ?></a>
    <?php } else {
        echo 0;
    }
	exit;
?>
