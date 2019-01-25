<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/rank_2.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/filtered_loot_count.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/loot_types.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/users.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/characters.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/items.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user_loot_logs.php";
	
	// unset invalid character / user IDs
	if (isset($_GET['user']) && $_GET['user'] <= 0) {
		unset($_GET['user']);
	}
	if (isset($_GET['character']) && $_GET['character'] <= 0) {
		unset($_GET['character']);
	}
	
	// number of results per page
	$results_per_page = 50;
	
	// calculate number of pages
	$number_of_pages = ceil(mysqli_num_rows($loot_count) / $results_per_page);
	
	// current page
	if (!isset($_GET['page']) || $_GET['page'] < 1) {
		$current_page = 1;
	} else if ($_GET['page'] > $number_of_pages) {
		$current_page = $number_of_pages;
	} else {
		$current_page = $_GET['page'];
	}
	
	// calculate offset
	$offset = ($current_page - 1) * $results_per_page;
	
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/filtered_loot.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_close.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_close.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Spire</title>
		<link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="/src/css/style.css"></link>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
		<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
	</head>
	<body>
		<div id="wrapper">
			<?php require $_SERVER['DOCUMENT_ROOT'] . "/src/php/header.php"; ?>
			<div id="inner-wrapper">
				<?php require $_SERVER['DOCUMENT_ROOT'] . "/src/php/navigation.php"; ?>
				<div id="content">
					
					<div class="pre-article-container">
						<div class="left">
							<?php if ($user['security'] >= 2) { ?>
							<input type="button" class="pre-article-button" id="add-loot-btn" value="ADD LOOT"></input>
							<form method="POST" action="/newLootLog.php" target="_BLANK" onsubmit="return confirm('Are you sure you want to begin a new loot log?');" style="display: inline-block;">
								<input type="submit" class="pre-article-button" id="start-loot-log-btn" value="START LOOT LOG"></input>
							</form>
							<?php } ?>
						</div>
						<div class="right">
							
						</div>
					</div>
					
					<?php if ($user['security'] >= 2 && mysqli_num_rows($user_loot_logs) > 0) { ?>
					<article>
						<div class="header">
							<h4>Loot Logs</h4>
						</div>
						<div class="body">
							<div class="active-loot-logs-container">
								<?php while ($ull = mysqli_fetch_array($user_loot_logs)) { 
								$ull_date = new DateTime($ull['started']);
								?>
								<a href="/lootLog.php?id=<?php echo $ull['id']; ?>" target="_BLANK">Started <?php echo $ull_date->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d H:i:s'); ?> (<?php echo $ull['itemsCount']; ?> items)</a>
								<?php } ?>
							</div>
						</div>
					</article>
					<?php } ?>
					
					<article>
						<div class="header">
							<h4>Filter</h4>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<table id="filter-loot-table">
									<tr>
										<td><b>User:</b></td>
										<td>
											<form method="GET" action="">
												<select name="user" class="standard-select" onchange="this.form.submit();">
													<option value="0">ALL USERS</option>
													<?php while ($u = mysqli_fetch_array($users)) { ?>
													<option value="<?php echo $u['id']; ?>" <?php if (isset($_GET['user']) && $_GET['user'] == $u['id']) echo "selected"; ?>><?php echo $u['username']; ?></option>
													<?php } ?>
												</select>
											</form>
										</td>
										<td><b>Character:</b></td>
										<td>
											<form method="GET" action="">
												<input type="hidden" name="user" value="<?php if (isset($_GET['user'])) echo $_GET['user']; ?>"></input>
												<select name="character" class="standard-select" onchange="this.form.submit();">
													<option value="0">ALL CHARACTERS</option>
													<?php 
													mysqli_data_seek($characters, 0);
													while ($c = mysqli_fetch_array($characters)) { 
														if (isset($_GET['user']) && $_GET['user'] == $c['user_id']) {
													?>
													<option value="<?php echo $c['id']; ?>" <?php if (isset($_GET['character']) && $_GET['character'] == $c['id']) echo "selected"; ?>><?php echo $c['name']; ?></option>
													<?php }
													} ?>
												</select>
											</form>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</article>
					<article>
						<div class="header">
							<h4>Loot Log</h4>
						</div>
						<?php if ($number_of_pages > 1) { ?>
						<div class="pager-container">
							<?php if ($current_page > 1) { ?>
							<form method="GET" action="" style="float: left;">
								<?php if (isset($_GET['user'])) { ?>
								<input type="hidden" name="user" value="<?php echo $_GET['user']; ?>"></input>
								<?php } ?>
								<?php if (isset($_GET['character'])) { ?>
								<input type="hidden" name="character" value="<?php echo $_GET['character']; ?>"></input>
								<?php } ?>
								<input type="hidden" name="page" value="<?php echo $current_page - 1; ?>"></input>
								<input type="submit" class="pager-button left" value="PREV"></input>
							</form>
							<?php } ?>
							
							
							<?php if ($current_page < $number_of_pages) { ?>
							<form method="GET" action="" style="float: right;">
								<?php if (isset($_GET['user'])) { ?>
								<input type="hidden" name="user" value="<?php echo $_GET['user']; ?>"></input>
								<?php } ?>
								<?php if (isset($_GET['character'])) { ?>
								<input type="hidden" name="character" value="<?php echo $_GET['character']; ?>"></input>
								<?php } ?>
								<input type="hidden" name="page" value="<?php echo $current_page + 1; ?>"></input>
								<input type="submit" class="pager-button right" value="NEXT"></input>
							</form>
							<?php } ?>
							
							<p class="pager-number-title">PAGES</p>
							<?php for ($i = 1; $i <= $number_of_pages; $i++) { ?>
							<span class="pager-page-number <?php if ($i == $current_page) echo "active"; ?>"><?php echo $i; ?></span>
							<?php } ?>
						</div>
						<?php } ?>
						<div class="body">
							<div class="scrolling-table-container">
								<table id="loot-log-table">
									<thead>
										<tr>
											<th>Date</th>
											<th>User</th>
											<th>Character</th>
											<th>Item</th>
											<th>Type</th>
											<th>Cost</th>
											<th>Note</th>
										</tr>
									</thead>
									<tbody>
										<?php while ($l = mysqli_fetch_array($loot)) { 
											$l_date = new DateTime($l['timestamp']);
										?>
										<tr>
											<td><?php echo $l_date->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d'); ?></td>
											<td><a href="/viewUser?id=<?php echo $l['user_id']; ?>"><?php echo $l['username']; ?></a></td>
											<td><?php echo $l['characterName']; ?></td>
											<td><a href="https://classicdb.ch/?item=<?php echo $l['item_id']; ?>" target="_BLANK" class="quality-<?php echo $l['quality']; ?>"><?php echo $l['itemName']; ?></a></td>
											<td><?php echo $l['typeName']; ?></td>
											<td><?php echo $l['cost']; ?></td>
											<td><?php echo $l['note']; ?></td>
											<?php if ($user['security'] >= 2) { ?>
											<td>
												<input type="button" class="standard-button edit-loot-btn" value="EDIT" data-id="<?php echo $l['id']; ?>" data-date="<?php echo $l_date->setTimezone($LOCAL_TIMEZONE)->format('Y-m-d H:i'); ?>" data-character="<?php echo $l['character_id']; ?>" data-item="<?php echo $l['item_id']; ?>" data-type="<?php echo $l['type']; ?>"></input>
												<input type="button" class="standard-button delete-loot-btn" value="DELETE" data-id="<?php echo $l['id']; ?>"></input>
											</td>
											<?php } ?>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</article>
				</div>
			</div>
		</div>	
		<?php if ($user['security'] >= 2) { ?>
		<div class="full-overlay" id="overlay-add-loot">
			<div class="scrolling-table-container">
				<h2>Add Loot</h2>
				<form method="POST" id="add-loot-form" action="/src/php/add_loot.php"></form>
				<table>
					<tr>
						<td><b>Date</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" class="standard-input" id="loot-date" form="add-loot-form" name="loot_date" required></input>
								<span>Enter using your local timezone: <b><?php echo $user['timezone_name']; ?></b>. Your local time can be changed from your Profile.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Character</b>:</td>
						<td>
							<div class="table-cell">
								<select class="standard-select" id="loot-character" form="add-loot-form" name="loot_character" required>
									<?php 
									mysqli_data_seek($characters, 0);
									while ($c = mysqli_fetch_array($characters)) {
									echo "<option value='" . $c['id'] . "'>" . $c['name'] . "</option>";
									} ?>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Item</b>:</td>
						<td>
							<div class="table-cell">
								<select class="standard-select" id="loot-item" form="add-loot-form" name="loot_item" required>
									<?php while ($i = mysqli_fetch_array($items)) {
									echo "<option value='" . $i['id'] . "'>" . $i['name'] . "</option>";
									} ?>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Type</b>:</td>
						<td>
							<div class="table-cell">
								<select class="standard-select" id="loot-type" form="add-loot-form" name="loot_type" required>
									<?php 
									mysqli_data_seek($loot_types, 0);
									while ($lt = mysqli_fetch_array($loot_types)) {
									echo "<option value='" . $lt['id'] . "'>" . $lt['name'] . "</option>";
									} ?>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Note</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" class="standard-input" id="loot-note" form="add-loot-form" name="loot_note"></input>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">						
							<input type="submit" class="standard-button" form="add-loot-form" value="SUBMIT"></input>
							<input type="button" class="standard-button close-overlay-btn" value="CANCEL"></input>
						</td>
					</tr>
				</table>
			</div>
		</div>	
		
		<div class="full-overlay" id="overlay-edit-loot">
			<div class="scrolling-table-container">
				<h2>Edit Loot</h2>
				<form method="POST" id="edit-loot-form" action="/src/php/edit_loot.php"></form>
				<input type="hidden" id="edit-loot-id" name="loot_id" form="edit-loot-form"></input>
				<table>
					<tr>
						<td><b>Date</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" class="standard-input" id="edit-loot-date"  name="loot_date" disabled></input>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Character</b>:</td>
						<td>
							<div class="table-cell">
								<select class="standard-select" id="edit-loot-character" form="edit-loot-form" name="loot_character" required>
									<?php 
									mysqli_data_seek($characters, 0);
									while ($c = mysqli_fetch_array($characters)) {
									echo "<option value='" . $c['id'] . "'>" . $c['name'] . "</option>";
									} ?>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Item</b>:</td>
						<td>
							<div class="table-cell">
								<select class="standard-select" id="edit-loot-item" name="loot_item" form="edit-loot-form" required>
									<?php 
									mysqli_data_seek($items, 0);
									while ($i = mysqli_fetch_array($items)) { 
									echo "<option value='" . $i['id'] . "'>" . $i['name'] . "</option>";
									} ?>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Type</b>:</td>
						<td>
							<div class="table-cell">
								<select class="standard-select" id="edit-loot-type" name="loot_type" form="edit-loot-form" required>
									<?php 
									mysqli_data_seek($loot_types, 0);
									while ($lt = mysqli_fetch_array($loot_types)) {
									echo "<option value='" . $lt['id'] . "'>" . $lt['name'] . "</option>";
									} ?>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" class="standard-button" form="edit-loot-form" value="SUBMIT"></input>
							<input type="button" class="standard-button close-overlay-btn" value="CANCEL"></input>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<?php } ?>	
		<script>
			
			// datetime selector
			$('#loot-date').flatpickr({
				enableTime: true,
				time_24hr: true,
				altInput: true,
				altFormat: 'Y-m-d H:i',
				dateFormat: 'Y-m-d H:i:S'
			});
			
			// toggle overlays
			$('#add-loot-btn').click(function(){
				$('#overlay-add-loot').slideDown(250);
			});
			$('.close-overlay-btn').click(function(){
				$('.full-overlay').slideUp(250);
			});
			
			// open edit overlay
			$('.edit-loot-btn').click(function(){
				let editID = $(this).data('id');
				let editDate = $(this).data('date');
				let editCharacter = $(this).data('character');
				let editItem = $(this).data('item');
				let editType = $(this).data('type');
				
				$('#edit-loot-id').val(editID);
				$('#edit-loot-date').val(editDate);
				$('#edit-loot-character').val(editCharacter);
				$('#edit-loot-item').val(editItem);
				$('#edit-loot-type').val(editType);
				
				$('#overlay-edit-loot').slideDown(250);
			});
			
			// delete loot
			$('.delete-loot-btn').click(function(){
				if (confirm("Are you sure you want to delete this loot?")) {
					
					let lootID = $(this).data('id');
					let par = $(this).parent().parent();;
					let rowData = { 'loot_id': lootID };
					$.ajax({
						url: "/src/php/delete_loot.php",
						type: "POST",
						data: rowData,
						success: function(d) {
							if (d == 0) {
								par.remove();
							} else {
								alert("Oops! Something went wrong. Error code: " + d);
							}
						}
					});
				}
			});
		</script>	
	</body>
</html>
