<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	
	$error = false;
	
	// clean sort order variable
	if ($_POST['order'] == "DESC") {
		$order = "DESC";
	} else {
		$order = "ASC";
	}
	
	// get user attendance count
	$stmt->prepare("SELECT COUNT(*) count FROM `attendance` WHERE `user_id` = ?");
	if (!($stmt->bind_param("i", $_POST['id']))) {
		$error = true;
	} else if (!($stmt->execute())) {
		$error = true;
	} else {
		$result = mysqli_fetch_array($stmt->get_result());
		$user_attendance_count = $result['count'];
	}
	
	// configure sorting
	switch ($_POST['sort']) {
		case "event":
			$sort = "t2.`title`";
			break;
		case "type":
			$sort = "event_type";
			break;
		case "attendance":
			$sort = "attendance_type";
			break;
		case "value":
			$sort = "t5.`value`";
			break;
		default:
			$sort = "t2.`date`";
			break;
	}
	
	// get player attendance
	if (!$error) {
		$stmt->prepare("SELECT t1.`id`, t1.`attendance_log_id`, t2.`title`, t2.`date`, t3.`name` as event_type, t1.`type` as attendance_type, t4.`name` as attendance_name, t5.`value`
								FROM `attendance` t1
									INNER JOIN `attendance_log` t2
										ON t2.`id` = t1.`attendance_log_id`
									INNER JOIN `event_types` t3
										ON t3.`id` = t2.`type`
									INNER JOIN `attendance_types` t4
										ON t4.`id` = t1.`type`
									INNER JOIN `attendance_values` t5
										ON t5.`event_type_id` = t2.`type` AND t5.`attendance_type_id` = t1.`type`
								WHERE t1.`user_id` = ? ORDER BY " . $sort . " " . $order . " LIMIT ? OFFSET ?");
		if (!($stmt->bind_param("iii", $_POST['id'], $_POST['limit'], $_POST['offset']))) {
			$error = true;
		} else if (!($stmt->execute())) {
			$error = true;
		} else {
			$user_attendance = $stmt->get_result();
		}
	}
	
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
	
	
	if ($error) {
		echo "error";
	} else {
		echo "{" . $user_attendance_count . "}";
		while ($a = mysqli_fetch_array($user_attendance)) {
			$ad = new DateTime($a['date']);
			echo "<tr>";
			echo "<td>" . $ad->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d H:i') . "</td>";
			echo "<td>" . $a['title'] . "</td>";
			echo "<td>" . $a['event_type'] . "</td>";
			echo "<td>" . $a['attendance_name'] . "</td>";
			echo "<td>";
			if ($a['value'] > 0) echo "+"; else if ($a['value'] < 0) echo "-";  echo $a['value'];
			echo "</td>";
			echo "</tr>";
		}
	}
?>
