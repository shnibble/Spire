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
	
	// get user loot count
	$stmt->prepare("SELECT COUNT(*) count FROM `users` WHERE `active` = TRUE and `rank` > 1");
	if (!($stmt->execute())) {
		$error = true;
	} else {
		$result = mysqli_fetch_array($stmt->get_result());
		$users_count = $result['count'];
	}
	
	// get user attendance
	if (!$error) {
		switch ($_POST['sort']) {
			case "character":
				$stmt->prepare("SELECT t1.`id`, t1.`active`, t1.`username`, t1.`joined`, t1.`last_login`, t1.`rank`, t2.`name` as rankName, t1.`security`, t1.`timezone_id`, t3.`name` as timezoneName, t4.`name` as characterName, (SELECT GROUP_CONCAT(`badge_id` ORDER BY `badge_id`) FROM `user_badges` WHERE `user_id` = t1.`id`) badges, t5.`loot_cost`, t5.`attendance_score`, (t5.`attendance_score` - t5.`loot_cost`) spread, t5.`30_day_attendance_rate` 
							FROM `users` t1
								INNER JOIN `ranks` t2
									ON t2.`id` = t1.`rank`
								INNER JOIN `timezones` t3
									ON t3.`id` = t1.`timezone_id`
								INNER JOIN `characters` t4
									ON t4.`user_id` = t1.`id` AND t4.`main` = TRUE
								INNER JOIN `user_scores` t5 
									ON t5.`user_id` = t1.`id` 
							WHERE t1.`active` = TRUE AND t1.`rank` > 1 ORDER BY characterName " . $order . " LIMIT ? OFFSET ?");
				break;
			case "rank":
				$stmt->prepare("SELECT t1.`id`, t1.`active`, t1.`username`, t1.`joined`, t1.`last_login`, t1.`rank`, t2.`name` as rankName, t1.`security`, t1.`timezone_id`, t3.`name` as timezoneName, t4.`name` as characterName, (SELECT GROUP_CONCAT(`badge_id` ORDER BY `badge_id`) FROM `user_badges` WHERE `user_id` = t1.`id`) badges, t5.`loot_cost`, t5.`attendance_score`, (t5.`attendance_score` - t5.`loot_cost`) spread, t5.`30_day_attendance_rate` 
							FROM `users` t1
								INNER JOIN `ranks` t2
									ON t2.`id` = t1.`rank`
								INNER JOIN `timezones` t3
									ON t3.`id` = t1.`timezone_id`
								INNER JOIN `characters` t4
									ON t4.`user_id` = t1.`id` AND t4.`main` = TRUE
								INNER JOIN `user_scores` t5 
									ON t5.`user_id` = t1.`id` 
							WHERE t1.`active` = TRUE AND t1.`rank` > 1 ORDER BY t1.`rank` " . $order . " LIMIT ? OFFSET ?");
				break;
			case "loot":
				$stmt->prepare("SELECT t1.`id`, t1.`active`, t1.`username`, t1.`joined`, t1.`last_login`, t1.`rank`, t2.`name` as rankName, t1.`security`, t1.`timezone_id`, t3.`name` as timezoneName, t4.`name` as characterName, (SELECT GROUP_CONCAT(`badge_id` ORDER BY `badge_id`) FROM `user_badges` WHERE `user_id` = t1.`id`) badges, t5.`loot_cost`, t5.`attendance_score`, (t5.`attendance_score` - t5.`loot_cost`) spread, t5.`30_day_attendance_rate` 
							FROM `users` t1
								INNER JOIN `ranks` t2
									ON t2.`id` = t1.`rank`
								INNER JOIN `timezones` t3
									ON t3.`id` = t1.`timezone_id`
								INNER JOIN `characters` t4
									ON t4.`user_id` = t1.`id` AND t4.`main` = TRUE
								INNER JOIN `user_scores` t5 
									ON t5.`user_id` = t1.`id` 
							WHERE t1.`active` = TRUE AND t1.`rank` > 1 ORDER BY t5.`loot_cost` " . $order . " LIMIT ? OFFSET ?");
				break;
			case "attendance":
				$stmt->prepare("SELECT t1.`id`, t1.`active`, t1.`username`, t1.`joined`, t1.`last_login`, t1.`rank`, t2.`name` as rankName, t1.`security`, t1.`timezone_id`, t3.`name` as timezoneName, t4.`name` as characterName, (SELECT GROUP_CONCAT(`badge_id` ORDER BY `badge_id`) FROM `user_badges` WHERE `user_id` = t1.`id`) badges, t5.`loot_cost`, t5.`attendance_score`, (t5.`attendance_score` - t5.`loot_cost`) spread, t5.`30_day_attendance_rate` 
							FROM `users` t1
								INNER JOIN `ranks` t2
									ON t2.`id` = t1.`rank`
								INNER JOIN `timezones` t3
									ON t3.`id` = t1.`timezone_id`
								INNER JOIN `characters` t4
									ON t4.`user_id` = t1.`id` AND t4.`main` = TRUE
								INNER JOIN `user_scores` t5 
									ON t5.`user_id` = t1.`id` 
							WHERE t1.`active` = TRUE AND t1.`rank` > 1 ORDER BY t5.`attendance_score` " . $order . " LIMIT ? OFFSET ?");
				break;
			case "30dar":
				$stmt->prepare("SELECT t1.`id`, t1.`active`, t1.`username`, t1.`joined`, t1.`last_login`, t1.`rank`, t2.`name` as rankName, t1.`security`, t1.`timezone_id`, t3.`name` as timezoneName, t4.`name` as characterName, (SELECT GROUP_CONCAT(`badge_id` ORDER BY `badge_id`) FROM `user_badges` WHERE `user_id` = t1.`id`) badges, t5.`loot_cost`, t5.`attendance_score`, (t5.`attendance_score` - t5.`loot_cost`) spread, t5.`30_day_attendance_rate` 
							FROM `users` t1
								INNER JOIN `ranks` t2
									ON t2.`id` = t1.`rank`
								INNER JOIN `timezones` t3
									ON t3.`id` = t1.`timezone_id`
								INNER JOIN `characters` t4
									ON t4.`user_id` = t1.`id` AND t4.`main` = TRUE
								INNER JOIN `user_scores` t5 
									ON t5.`user_id` = t1.`id` 
							WHERE t1.`active` = TRUE AND t1.`rank` > 1 ORDER BY t5.`30_day_attendance_rate` " . $order . " LIMIT ? OFFSET ?");
				break;
			case "joined":
				$stmt->prepare("SELECT t1.`id`, t1.`active`, t1.`username`, t1.`joined`, t1.`last_login`, t1.`rank`, t2.`name` as rankName, t1.`security`, t1.`timezone_id`, t3.`name` as timezoneName, t4.`name` as characterName, (SELECT GROUP_CONCAT(`badge_id` ORDER BY `badge_id`) FROM `user_badges` WHERE `user_id` = t1.`id`) badges, t5.`loot_cost`, t5.`attendance_score`, (t5.`attendance_score` - t5.`loot_cost`) spread, t5.`30_day_attendance_rate` 
							FROM `users` t1
								INNER JOIN `ranks` t2
									ON t2.`id` = t1.`rank`
								INNER JOIN `timezones` t3
									ON t3.`id` = t1.`timezone_id`
								INNER JOIN `characters` t4
									ON t4.`user_id` = t1.`id` AND t4.`main` = TRUE
								INNER JOIN `user_scores` t5 
									ON t5.`user_id` = t1.`id` 
							WHERE t1.`active` = TRUE AND t1.`rank` > 1 ORDER BY t1.`joined` " . $order . " LIMIT ? OFFSET ?");
				break;
			case "lastlogin":
				$stmt->prepare("SELECT t1.`id`, t1.`active`, t1.`username`, t1.`joined`, t1.`last_login`, t1.`rank`, t2.`name` as rankName, t1.`security`, t1.`timezone_id`, t3.`name` as timezoneName, t4.`name` as characterName, (SELECT GROUP_CONCAT(`badge_id` ORDER BY `badge_id`) FROM `user_badges` WHERE `user_id` = t1.`id`) badges, t5.`loot_cost`, t5.`attendance_score`, (t5.`attendance_score` - t5.`loot_cost`) spread, t5.`30_day_attendance_rate` 
							FROM `users` t1
								INNER JOIN `ranks` t2
									ON t2.`id` = t1.`rank`
								INNER JOIN `timezones` t3
									ON t3.`id` = t1.`timezone_id`
								INNER JOIN `characters` t4
									ON t4.`user_id` = t1.`id` AND t4.`main` = TRUE
								INNER JOIN `user_scores` t5 
									ON t5.`user_id` = t1.`id` 
							WHERE t1.`active` = TRUE AND t1.`rank` > 1 ORDER BY t1.`last_login` " . $order . " LIMIT ? OFFSET ?");
				break;
			case "security":
				$stmt->prepare("SELECT t1.`id`, t1.`active`, t1.`username`, t1.`joined`, t1.`last_login`, t1.`rank`, t2.`name` as rankName, t1.`security`, t1.`timezone_id`, t3.`name` as timezoneName, t4.`name` as characterName, (SELECT GROUP_CONCAT(`badge_id` ORDER BY `badge_id`) FROM `user_badges` WHERE `user_id` = t1.`id`) badges, t5.`loot_cost`, t5.`attendance_score`, (t5.`attendance_score` - t5.`loot_cost`) spread, t5.`30_day_attendance_rate` 
							FROM `users` t1
								INNER JOIN `ranks` t2
									ON t2.`id` = t1.`rank`
								INNER JOIN `timezones` t3
									ON t3.`id` = t1.`timezone_id`
								INNER JOIN `characters` t4
									ON t4.`user_id` = t1.`id` AND t4.`main` = TRUE
								INNER JOIN `user_scores` t5 
									ON t5.`user_id` = t1.`id` 
							WHERE t1.`active` = TRUE AND t1.`rank` > 1 ORDER BY t1.`security` " . $order . " LIMIT ? OFFSET ?");
				break;
			case "timezone":
				$stmt->prepare("SELECT t1.`id`, t1.`active`, t1.`username`, t1.`joined`, t1.`last_login`, t1.`rank`, t2.`name` as rankName, t1.`security`, t1.`timezone_id`, t3.`name` as timezoneName, t4.`name` as characterName, (SELECT GROUP_CONCAT(`badge_id` ORDER BY `badge_id`) FROM `user_badges` WHERE `user_id` = t1.`id`) badges, t5.`loot_cost`, t5.`attendance_score`, (t5.`attendance_score` - t5.`loot_cost`) spread, t5.`30_day_attendance_rate` 
							FROM `users` t1
								INNER JOIN `ranks` t2
									ON t2.`id` = t1.`rank`
								INNER JOIN `timezones` t3
									ON t3.`id` = t1.`timezone_id`
								INNER JOIN `characters` t4
									ON t4.`user_id` = t1.`id` AND t4.`main` = TRUE
								INNER JOIN `user_scores` t5 
									ON t5.`user_id` = t1.`id` 
							WHERE t1.`active` = TRUE AND t1.`rank` > 1 ORDER BY timezoneName " . $order . " LIMIT ? OFFSET ?");
				break;
			default:
				$stmt->prepare("SELECT t1.`id`, t1.`active`, t1.`username`, t1.`joined`, t1.`last_login`, t1.`rank`, t2.`name` as rankName, t1.`security`, t1.`timezone_id`, t3.`name` as timezoneName, t4.`name` as characterName, (SELECT GROUP_CONCAT(`badge_id` ORDER BY `badge_id`) FROM `user_badges` WHERE `user_id` = t1.`id`) badges, t5.`loot_cost`, t5.`attendance_score`, (t5.`attendance_score` - t5.`loot_cost`) spread, t5.`30_day_attendance_rate` 
							FROM `users` t1
								INNER JOIN `ranks` t2
									ON t2.`id` = t1.`rank`
								INNER JOIN `timezones` t3
									ON t3.`id` = t1.`timezone_id`
								INNER JOIN `characters` t4
									ON t4.`user_id` = t1.`id` AND t4.`main` = TRUE
								INNER JOIN `user_scores` t5 
									ON t5.`user_id` = t1.`id` 
							WHERE t1.`active` = TRUE AND t1.`rank` > 1 ORDER BY t1.`username` " . $order . " LIMIT ? OFFSET ?");
				break;
		}
		if (!($stmt->bind_param("ii", $_POST['limit'], $_POST['offset']))) {
			$error = true;
		} else if (!($stmt->execute())) {
			$error = true;
		} else {
			$users = $stmt->get_result();
		}
	}
	
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
	
	
	if ($error) {
		echo "error";
	} else {
		echo "{" . $users_count . "}";
		while ($u = mysqli_fetch_array($users)) {
			$u_joined = new DateTime($u['joined']);
			if ($u['last_login']) {
				$u_lastLogin = new DateTime($u['last_login']);
			}
			echo "<tr>";
			echo "<td><a href='/viewUser?id=" . $u['id'] . "'>" . $u['username'] . "</a></td>";
			echo "<td>" . $u['characterName'] . "</td>";
			echo "<td>" . $u['rankName'] . "</td>";
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
			echo "<td><a href='/loot.php?user=" . $u['id'] . "'>";
			
			if ($u['loot_cost']) {
				echo "-" . $u['loot_cost'];
			} else {
				echo "0";
			}
			echo "</a></td>";
			echo "<td>+" . $u['attendance_score'] . "</td>";
			echo "<td>" . round((float)$u['30_day_attendance_rate'] * 100 ) . '%' . "</td>";
			echo "<td>" . $u_joined->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d') . "</td>";
			
			if ($user['security'] >= 2) {
				echo "<td>";
					if ($u['last_login']) {
						echo $u_lastLogin->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d'); 
					} else {
						echo "Never";
					}
				echo "</td>";
				echo "<td>" . $u['security'] . "</td>";
				echo "<td>" . $u['timezoneName'] . "</td>";
			}
			echo "</tr>";
		}
	}
?>
