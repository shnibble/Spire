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
	
	// configure filters
	$filter = "";
	
	// configure sorting
	switch ($_POST['sort']) {
		case "character":
			$sort = "character_name";
			break;
		case "item":
			$sort = "t2.`name`";
			break;
		case "type":
			$sort = "t1.`type`";
			break;
		case "cost":
			$sort = "t3.`cost`";
			break;
		default:
			$sort = "t1.`timestamp`";
			break;
	}
	
	// get player loot count
	$stmt->prepare("SELECT COUNT(*) count FROM `loot` t1 WHERE t1.`enabled` = TRUE and t1.`character_id` IN (SELECT `id` FROM `characters` WHERE `user_id` = ?) " . $filter . "");
	if (!($stmt->bind_param("i", $_POST['id']))) {
		$error = true;
	} else if (!($stmt->execute())) {
		$error = true;
	} else {
		$result = mysqli_fetch_array($stmt->get_result());
		$user_loot_count = $result['count'];
	}
	
	// get player loot
	if (!$error) {
		$stmt->prepare("SELECT t1.`id`, t1.`timestamp`, t1.`character_id`, t4.`name` as character_name, t1.`item_id`, t2.`name`, t2.`quality`, t1.`type`, t3.`name` as type_name, t3.`cost`, t1.`note`
					FROM `loot` t1
						INNER JOIN `items` t2
							ON t2.`id` = t1.`item_id`
						INNER JOIN `loot_types` t3
							ON t3.`id` = t1.`type`
						INNER JOIN `characters` t4
							ON t4.`id` = t1.`character_id`
					WHERE t1.`enabled` = TRUE AND t1.`character_id` IN (SELECT `id` FROM `characters` WHERE `user_id` = ?) " . $filter . " ORDER BY " . $sort . " " . $order . " LIMIT ? OFFSET ?");
		if (!($stmt->bind_param("iii", $_POST['id'], $_POST['limit'], $_POST['offset']))) {
			$error = true;
		} else if (!($stmt->execute())) {
			$error = true;
		} else {
			$user_loot = $stmt->get_result();
		}
	}
	
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
	
	
	if ($error) {
		echo "error";
	} else {
		echo "{" . $user_loot_count . "}";
		while ($l = mysqli_fetch_array($user_loot)) {
			$ld = new DateTime($l['timestamp']);
			echo "<tr>";
			echo "<td>" . $ld->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d H:i') . "</td>";
			echo "<td>" . $l['character_name'] . "</td>";
			echo "<td><a href='https://classicdb.ch/?item=" . $l['item_id'] ."' class='quality-" . $l['quality'] . "' target='_BLANK'>" . $l['name'] . "</a></td>";
			echo "<td>" . $l['type_name'] . "</td>";
			echo "<td>" . $l['cost'] . "</td>";
			echo "</tr>";
		}
	}
?>
