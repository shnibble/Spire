<?php
	if (!isset($_GET['id']) || $_GET['id'] == "") { 
		header("Location: /error.php?id=110");
		exit;
	}
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/check_login_token.php";
	if (!checkLoginToken($stmt)) { header("Location: /?url=" . $_SERVER['REQUEST_URI']); exit; }
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/security_1.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/characters.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/items.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/loot_types.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/loot_log.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/loot_log_items.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
	
	if (!$valid_id) { 
		header("Location: /error.php?id=110");
		exit;
	}
	
	$started_date = new DateTime($loot_log['started']);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Spire Loot Log</title>
		<link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="/src/css/style-lootlog.css"></link>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="/src/js/timeout.js"></script>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
		<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
	</head>
	<body>
		<div id="header">
			<a href="/loot">Back to Main Site</a>
			<h1>Loot Log</h1>
			<table>
				<tr>
					<td><b>Log ID:</b></td>
					<td><input type="number" id="lootlog-id" value="<?php echo $loot_log['id']; ?>" readonly></input></td>
				</tr>
				<tr>
					<td><b>Started:</b></td>
					<td><input type="text" value="<?php echo $started_date->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d H:i'); ?>" readonly></input></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="button" id="delete-loot-log-btn" value="DELETE LOOT LOG"></input></td>
				</tr>
			</table>
		</div>
		<div id="content">
			<table id="items-table">
				<tr>
					<th></th>
					<th>Character</th>
					<th>Item</th>
					<th>Type</th>
					<th>Priority</th>
					<th>Comment</th>
					<th>Note</th>
				</tr>
				<?php while ($lli = mysqli_fetch_array($loot_log_items)) { ?>
				<tr class="saved">
					<input type="hidden" class="id" value="<?php echo $lli['id']; ?>"></input>
					<td>
						<div class="status"></div>
					</td>
					<td>
						<select class="input-trigger character">
							<option value=""></option>
							<?php 
							mysqli_data_seek($characters, 0);
							while ($c = mysqli_fetch_array($characters)) { ?>
							<option value="<?php echo $c['id']; ?>" <?php if ($lli['character_id'] == $c['id']) echo "selected"; ?>><?php echo $c['name']; ?></option>
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
																	data-comment="<?php echo htmlspecialchars($i['comment']); ?>" 
									<?php if ($lli['item_id'] == $i['id']) echo "selected"; ?>><?php echo $i['name']; ?></option>
							<?php } ?>
						</select>
					</td>
					<td>
						<select class="input-trigger type">
							<?php 
							mysqli_data_seek($loot_types, 0);
							while ($lt = mysqli_fetch_array($loot_types)) { ?>
							<option value="<?php echo $lt['id']; ?>" <?php if ($lli['type'] == $lt['id']) echo "selected"; ?>><?php echo $lt['name']; ?></option>
							<?php } ?>
						</select>
					</td>
					<td>
						<span class="priority"><?php echo htmlspecialchars($lli['loot_priority']); ?></span>
					</td>
					<td>
						<span class="comment"><?php echo htmlspecialchars($lli['comment']); ?></span>
					</td>
					<td><input class="input-trigger note" type="text" value="<?php echo $lli['note']; ?>"></input></td>
					<td><input type="button" value="X" class="delete-row-btn"></input></td>
					<td><span class="alert" style="display: none; color: green;">Saved!</span></td>
				</tr>
				<?php } ?>
			</table>
			<input type="button" id="add-row-btn" value="ADD ROW"></input>
		</div>
		<div id="footer">
			<form method="POST" id="log-loot-form" action="/src/php/submit_loot_log.php" onsubmit="return confirm('Are you sure you want to submit this log?');">
				<table id="footer-table">
					<tr>
						<td>
							<h2>Finish and log all loot</h2>
						</td>
					</tr>
					<tr>
						<td>
							<input type="text" id="loot-date" name="loot_date" placeholder="LOOT DATE" required></input>
							<br><span>Enter using your local timezone: <b><?php echo $user['timezone_name']; ?></b>. Your local time can be changed from your Profile.</span>
						</td>
					</tr>
					<tr>
						<td>
							<input type="hidden" name="lootlog_id" value="<?php echo $loot_log['id']; ?>"></input>
							<input type="submit" form="log-loot-form" id="submit-loot-log-btn" value="SUBMIT"></input>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<script>
			// datetime selector
			$('#loot-date').flatpickr({
				enableTime: true,
				time_24hr: true,
				altInput: true,
				altFormat: 'Y-m-d H:i',
				dateFormat: 'Y-m-d H:i:S'
			});
			
			$('#items-table').on('input', '.input-trigger', function(){
				$(this).parent('td').parent('tr').removeClass();
				$(this).parent('td').parent('tr').addClass("editing");
			});
			
			$('#items-table').on('change', '.input-trigger', function(){
				let row = $(this).parent('td').parent('tr');
				
				let id 			= row.children('.id').val();
				let character 	= row.children('td').children('.character').val();
				let item 		= row.children('td').children('.item').val();
				let type	 	= row.children('td').children('.type').val();
				let note	 	= row.children('td').children('.note').val();
				
				row.removeClass();
				row.addClass("submitted");
				updateRow(row, id, character, item, type, note);
			});
			
			function alertSuccessOnRow(row) {
				row.children('td').children('.alert').show();
				setTimeout(function(){
					row.children('td').children('.alert').fadeOut(1500);;
				}, 500);
			}
			
			function updateRow(row, id, characterID, itemID, type, note){
				let rowData = { 'id': id, 'character_id': characterID, 'item_id': itemID, 'type': type, 'note': note};
				$.ajax({
					url: "/src/php/update_loot_log_item.php",
					type: "POST",
					data: rowData,
					success: function(d) {
						if (d == 0) {
							row.removeClass();
							row.addClass("saved");
							alertSuccessOnRow(row);
						} else {
							alert("Something went wrong, data not saved! Error code: " + d);
						}
					}
				});
			}
			
			$('#items-table').on('click', '.delete-row-btn', function(){
				let row = $(this).parent('td').parent('tr');
				let id = row.children('.id').val();
				deleteRow(row, id);
			});
			
			function deleteRow(row, id){
				let rowData = { 'id': id };
				$.ajax({
					url: "/src/php/delete_loot_log_item.php",
					type: "POST",
					data: rowData,
					success: function(d) {
						if (d == "success") {
							row.remove();
						} else {
							alert("Something went wrong, data not saved!");
						}
					}
				});
			}
			
			
			$('#add-row-btn').on('click', function(){
				let lootLogID 	= $('#lootlog-id').val();
				addRow(lootLogID);
			});
			
			function addRow(lootLogID){
				let rowData = { 'lootlog_id': lootLogID };
				$.ajax({
					url: "/src/php/add_loot_log_item.php",
					type: "POST",
					data: rowData,
					success: function(d) {
						if (d != "error") {
							$('#items-table tbody').append(d);
						} else {
							alert("Something went wrong, data not saved!");
						}
					}
				});
			}
			
			$('#delete-loot-log-btn').click(function(){
				if (confirm("Are you sure you want to delete this loot log? All items will be lost permanently.")) {
					
					let lootLogID = $('#lootlog-id').val();
					let rowData = { 'id': lootLogID };
					$.ajax({
						url: "/src/php/delete_loot_log.php",
						type: "POST",
						data: rowData,
						success: function(d) {
							if (d == 0) {
								window.location = "/loot";
							} else {
								alert("Oops! Something went wrong. Error code: " + d);
							}
						}
					});
				}
			});
			
			$('#submit-loot-log-btn').click(function(e){
				let chars = $('.character');
				let items = $('.item');
				let checker = false;
				
				chars.each(function(){
					if ($(this).val() == "") {
						checker = true;
					}
				});
				if (!checker) {
					items.each(function(){
						if ($(this).val() == "") {
							checker = true;
						}
					});
				}
				
				if (checker) {
					e.preventDefault();
					alert("You have some incomplete rows in the log. Please remove them or make sure each has an item and character selected.");
				} else if ($('#loot-date').val() == "") {
					e.preventDefault();
					alert("Please enter a loot date.");
				}
			});
			
			$('#items-table').delegate('.input-trigger.item', 'change', function(){
				let defaultType = $(this).children('option:selected').data('type');
				let priority = $(this).children('option:selected').data('priority');
				let comment = $(this).children('option:selected').data('comment');
				let typeField = $(this).parent().siblings().children('.input-trigger.type')
				let priorityField = $(this).parent().siblings().children('.priority')
				let commentField = $(this).parent().siblings().children('.comment')
				
				if (defaultType > 0) {
					typeField.val(defaultType);
				} else {
					typeField.val(1);
				}
				priorityField.text(priority);
				commentField.text(comment);
			});
		</script>
	</body>
</html>
