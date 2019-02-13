<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_1.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/characters.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/items.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/loot_types.php";
	
	$error = false;
	
	// get POST variables
	if (!isset($_POST['lootlog_id'])) {
		// ERROR: missing variable
		$error = true;
		$error_id = 110;
	}
	
	// add item
	if (!$error) {
		$stmt->prepare("INSERT INTO `loot_log_items` (`loot_log_id`) VALUES (?)");
		if (!$stmt->bind_param("i", $_POST['lootlog_id'])) {
			// ERROR: failed to bind parameters
			$error = true;
			$error_id = 109;
		} else if (!$stmt->execute()) {
			// ERROR: failed to insert into database
			$error = true;
			$error_id = 109;
		} else {
			$last_id = $conn->insert_id;
		}
	}
	$stmt->close();
	$conn->close();
	
	if (!$error) { ?>
		<tr class="saved">
			<input type="hidden" class="id" value="<?php echo $last_id; ?>"></input>
			<td>
				<div class="status"></div>
			</td>
			<td>
				<select class="input-trigger character">
					<option value=""></option>
					<?php 
					mysqli_data_seek($characters, 0);
					while ($c = mysqli_fetch_array($characters)) { ?>
					<option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
					<?php } ?>
				</select>
			</td>
			<td>
				<select class="input-trigger item">
					<option value=""></option>
					<?php 
					mysqli_data_seek($items, 0);
					while ($i = mysqli_fetch_array($items)) { ?>
					<option value="<?php echo $i['id']; ?>" data-type="<?php echo $i['default_type']; ?>" 
															data-priority="<?php echo htmlspecialchars($i['loot_priority']); ?>" 
															data-comment="<?php echo htmlspecialchars($i['comment']); ?>"><?php echo $i['name']; ?></option>
					<?php } ?>
				</select>
			</td>
			<td>
				<select class="input-trigger type">
					<?php 
					mysqli_data_seek($loot_types, 0);
					while ($lt = mysqli_fetch_array($loot_types)) { ?>
					<option value="<?php echo $lt['id']; ?>"><?php echo $lt['name']; ?></option>
					<?php } ?>
				</select>
			</td>
			<td>
				<span class="priority"></span>
			</td>
			<td>
				<span class="comment"></span>
			</td>
			<td><input class="input-trigger note" type="text"></input></td>
			<td><input type="button" value="X" class="delete-row-btn"></input></td>
			<td><span class="alert" style="display: none; color: green;">Saved!</span></td>
		</tr>
	<?php } else {
		echo "error";
	}
?>
