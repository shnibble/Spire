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
	$filter = "";
	
	// configure sorting
	switch ($_POST['sort']) {
		case "type":
			$sort = "t2.`name`";
			break;
		default:
			$sort = "t1.`name`";
			break;
	}
	
	// get items count
	$stmt->prepare("SELECT COUNT(*) count FROM `items` t1 WHERE t1.`enabled` = TRUE " . $filter . "");
	if (!($stmt->execute())) {
		$error = true;
	} else {
		$result = mysqli_fetch_array($stmt->get_result());
		$items_count = $result['count'];
	}
	
	// get items
	if (!$error) {
		$stmt->prepare("SELECT t1.`id`, t1.`name`, t1.`quality`, t1.`default_type`, t2.`name` default_type_name, t1.`loot_priority`, t1.`comment` 
					FROM `items` t1 
						INNER JOIN `loot_types` t2 
							ON t2.`id` = t1.`default_type` 
					WHERE t1.`enabled` = TRUE " . $filter . " ORDER BY " . $sort . " " . $order . " LIMIT ? OFFSET ?");
		if (!($stmt->bind_param("ii", $_POST['limit'], $_POST['offset']))) {
			$error = true;
		} else if (!($stmt->execute())) {
			$error = true;
		} else {
			$items = $stmt->get_result();
		}
	}
	
	if ($error) {
		echo "error";
	} else {
		echo "{" . $items_count . "}";
		while ($i = mysqli_fetch_array($items)) {
			echo "<tr>";
			echo "<td><b><a href='https://classicdb.ch/?item=" . $i['id'] . "' class='quality-" . $i['quality'] . "' target='_BLANK'>" . $i['name'] . "</a></b></td>";
			echo "<td>" . $i['default_type_name'] . "</td>";
			echo "<td>" . htmlspecialchars($i['loot_priority']) . "</td>";
			echo "<td>" . htmlspecialchars($i['comment']) . "</td>";
			if ($user['security'] >= 2) {
				echo "<td>";
				echo "<input type='button' class='standard-button edit-item-btn' value='EDIT' data-id='" . $i['id'] . "' data-name='" . $i['name'] . "' data-quality='" . $i['quality'] . "' data-type='" . $i['default_type'] . "' data-priority='" . htmlspecialchars($i['loot_priority']) . "' data-comment='" . htmlspecialchars($i['comment']) . "'></input>";
				echo "<input type='button' class='standard-button delete-item-btn' value='DELETE' data-id='" . $i['id'] . "'></input>";
				echo "</td>";
				}
			echo "</tr>";
		}
	}
?>
