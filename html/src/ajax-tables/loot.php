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
		case "user":
			$filter = "AND t3.`id` = " . $filterValue;
			break;
		case "character":
			$filter = "AND t2.`id` = " . $filterValue;
			break;
		case "class":
			$filter = "AND t2.`class` = " . $filterValue;
			break;
		case "item":
			$filter = "AND t4.`id` = " . $filterValue;
			break;
		default:
			$filter = "";
			break;
	}
	
	// configure sorting
	switch ($_POST['sort']) {
		case "user":
			$sort = "t3.`username`";
			break;
		case "character":
			$sort = "t2.`name`";
			break;
		case "item":
			$sort = "t4.`name`";
			break;
		case "type":
			$sort = "t5.`name`";
			break;
		default:
			$sort = "t1.`timestamp`";
			break;
	}
	
	// get loot count
	$stmt->prepare("SELECT COUNT(*) count FROM `loot` t1
							INNER JOIN `characters` t2
								ON t2.`id` = t1.`character_id`
							INNER JOIN `users` t3
								ON t3.`id` = t2.`user_id`
							INNER JOIN `items` t4
								ON t4.`id` = t1.`item_id`
							INNER JOIN `loot_types` t5
								ON t5.`id` = t1.`type`
						WHERE t1.`enabled` = TRUE " . $filter . "");
	if (!($stmt->execute())) {
		$error = true;
	} else {
		$result = mysqli_fetch_array($stmt->get_result());
		$loot_count = $result['count'];
	}
	
	// get loot
	if (!$error) {
		$stmt->prepare("SELECT t1.`id`, t1.`timestamp`, t1.`character_id`, t2.`name` as characterName, t2.`class` as characterClass, t2.`user_id`, t3.`username`, t1.`item_id`, t4.`name` as itemName, t4.`quality`, t1.`type`, t5.`name` as typeName, t5.`cost`, t1.`note`
						FROM `loot` t1
							INNER JOIN `characters` t2
								ON t2.`id` = t1.`character_id`
							INNER JOIN `users` t3
								ON t3.`id` = t2.`user_id`
							INNER JOIN `items` t4
								ON t4.`id` = t1.`item_id`
							INNER JOIN `loot_types` t5
								ON t5.`id` = t1.`type`
						WHERE t1.`enabled` = TRUE " . $filter . " ORDER BY " . $sort . " " . $order . ", t2.`name` ASC, t4.`name` ASC LIMIT ? OFFSET ?");
		if (!($stmt->bind_param("ii", $_POST['limit'], $_POST['offset']))) {
			$error = true;
		} else if (!($stmt->execute())) {
			$error = true;
		} else {
			$loot = $stmt->get_result();
		}
	}
	
	if ($error) {
		echo "error";
	} else {
		echo "{" . $loot_count . "}";
		while ($l = mysqli_fetch_array($loot)) { 
			$l_date = new DateTime($l['timestamp']);
			echo "<tr>";
			echo "<td>" . $l_date->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d') . "</td>";
			echo "<td><a href='/viewUser?id=" . $l['user_id'] ."'>" . $l['username'] . "</a></td>";
			echo "<td class='class-" . $l['characterClass'] . "'>" . $l['characterName'] . "</td>";
			echo "<td><a href='https://classicdb.ch/?item=" . $l['item_id'] . "' target='_BLANK' class='quality-" . $l['quality'] . "'>" . $l['itemName'] . "</a></td>";
			echo "<td>" . $l['typeName'] . "</td>";
			echo "<td>" . $l['cost'] . "</td>";
			echo "<td>" . $l['note'] . "</td>";
			if ($user['security'] >= 1) {
				echo "<td>";
				echo "<input type='button' class='standard-button edit-loot-btn' value='EDIT' data-id='" . $l['id'] . "' data-date='" . $l_date->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d H:i') . "' data-character='" . $l['character_id'] . "' data-item='" . $l['item_id'] . "' data-type='" . $l['type'] ."'></input>";
				if ($user['security'] >= 2) {
					echo "<input type='button' class='standard-button delete-loot-btn' value='DELETE' data-id='" . $l['id'] . "'></input>";
				}
				echo "</td>";
			}
			echo "</tr>";
		}
	}
?>
