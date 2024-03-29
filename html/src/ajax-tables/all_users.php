<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/badges.php";
	
	$error = false;
	
	// clean sort order variable
	if ($_POST['order'] == "DESC") {
		$order = "DESC";
	} else {
		$order = "ASC";
	}
	
	// clean filter value
	$x = filter_input(INPUT_POST, "filtervalue", FILTER_VALIDATE_INT);
	if ($x) {
		$filterValue = $x;
	} else {
		$_POST['filtertype'] = "";
		$filterValue = "";
	}
	
	// configure filters
	switch ($_POST['filtertype']) {
		case "rank":
			$filter = "t1.`rank` = " . $filterValue;
			break;
		case "class":
			$filter = "t4.`class` = " . $filterValue;
			break;
		case "security":
			$filter = "t1.`security` = " . $filterValue;
			break;
		default:
			$filter = "1";
			break;
	}
	
	// configure sorting
	switch ($_POST['sort']) {
		case "character":
			$sort = "characterName";
			break;
		case "rank":
			$sort = "t1.`rank`";
			break;
		case "joined":
			$sort = "t1.`joined`";
			break;
		case "lastlogin":
			$sort = "t1.`last_login`";
			break;
		case "security":
			$sort = "t1.`security`";
			break;
		case "timezone":
			$sort = "timezoneName";
			break;
		default:
			$sort = "t1.`username`";
			break;
	}
	
	// get users count
	$stmt->prepare("SELECT COUNT(*) count 
					FROM `users` t1
						INNER JOIN `ranks` t2
							ON t2.`id` = t1.`rank`
						INNER JOIN `timezones` t3
							ON t3.`id` = t1.`timezone_id`
						INNER JOIN `characters` t4
							ON t4.`user_id` = t1.`id` AND t4.`main` = TRUE
						LEFT JOIN `user_scores` t5 
							ON t5.`user_id` = t1.`id` 
					WHERE " . $filter . "");
	if (!($stmt->execute())) {
		$error = true;
		$error_id = 1;
	} else {
		$result = mysqli_fetch_array($stmt->get_result());
		$users_count = $result['count'];
	}
	
	// get users
	if (!$error) {
		$stmt->prepare("SELECT t1.`id`, t1.`active`, t1.`username`, t1.`joined`, t1.`last_login`, t1.`rank`, t2.`name` as rankName, t1.`security`, t1.`timezone_id`, t3.`name` as timezoneName, t4.`name` as characterName, t4.`class` as characterClass, (SELECT GROUP_CONCAT(`badge_id` ORDER BY `badge_id`) FROM `user_badges` WHERE `user_id` = t1.`id`) badges, t5.`loot_cost`, t5.`attendance_score`, (t5.`attendance_score` - t5.`loot_cost`) spread, t5.`30_day_attendance_rate` 
						FROM `users` t1
							INNER JOIN `ranks` t2
								ON t2.`id` = t1.`rank`
							INNER JOIN `timezones` t3
								ON t3.`id` = t1.`timezone_id`
							INNER JOIN `characters` t4
								ON t4.`user_id` = t1.`id` AND t4.`main` = TRUE
							LEFT JOIN `user_scores` t5 
								ON t5.`user_id` = t1.`id` 
						WHERE " . $filter . " ORDER BY " . $sort . " " . $order . " LIMIT ? OFFSET ?");
		if (!($stmt->bind_param("ii", $_POST['limit'], $_POST['offset']))) {
			$error = true;
			$error_id = 2;
		} else if (!($stmt->execute())) {
			$error = true;
			$error_id = 3;
		} else {
			$users = $stmt->get_result();
		}
	}
	
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
	
	
	if ($error) {
		echo "error";
		echo $error_id;
	} else {
		echo "{" . $users_count . "}";
		while ($u = mysqli_fetch_array($users)) {
			$u_joined = new DateTime($u['joined']);
			if ($u['last_login']) {
				$u_lastLogin = new DateTime($u['last_login']);
			}
			echo "<tr ";
			if (!$u['active']) echo "class='inactive'";
			echo ">";
			echo "<td><a href='/admin/viewUser?id=" . $u['id'] . "'>" . $u['username'] . "</a></td>";
			echo "<td class='class-" . $u['characterClass'] . "'>" . $u['characterName'] . "</td>";
			echo "<td class='rank-" . $u['rank'] . "'>" . $u['rankName'] . "</td>";
			echo "<td>";
			echo "<div class='user-badges-container'>";
			
			if ($u['badges']) {
			$_user_badges = explode(",", $u['badges']);
				foreach ($_user_badges as $b) {
				echo "<div class='user-badge' style='background-image: url(/src/img/badge_" . $b . ".png);' title='" . htmlspecialchars($badges_array[$b]['name']) . "\n" . htmlspecialchars($badges_array[$b]['description']) . "'></div>";
				}
			} 
			
			echo "</div>";
			echo "</td>";
			echo "<td>" . $u_joined->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d') . "</td>";
			echo "<td>";
				if ($u['last_login']) {
					echo $u_lastLogin->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d'); 
				} else {
					echo "Never";
				}
			echo "</td>";
			echo "<td>" . $u['security'] . "</td>";
			echo "<td>" . $u['timezoneName'] . "</td>";
			
			if ($u['active']) {
				echo "<td>Active</td>";
			} else {
				echo "<td>Deactivated</td>";
			}
			echo "</tr>";
		}
	}
?>
