<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/rank_2.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/timezones.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/loot_types.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/users.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/characters.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/classes.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/items.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user_loot_logs.php";
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
							<h4>Loot Log</h4>
						</div>
						<div class="body">
							<div class="scrolling-table-container">
								<div class="ajax-table-filter">
									<h5>Filter</h5>
									<select class="standard-select ajax-table-filter-select" data-filtertype="class">
										<option value="0">CLASS</option>
										<?php while ($cl = mysqli_fetch_array($classes)) { ?>
										<option value="<?php echo $cl['id']; ?>"><?php echo $cl['name']; ?></option>
										<?php } ?>
									</select>
									<select class="standard-select ajax-table-filter-select" data-filtertype="user">
										<option value="0">USER</option>
										<?php while ($u = mysqli_fetch_array($users)) { ?>
										<option value="<?php echo $u['id']; ?>"><?php echo $u['username']; ?></option>
										<?php } ?>
									</select>
									<select class="standard-select ajax-table-filter-select" data-filtertype="character">
										<option value="0">CHARACTER</option>
										<?php while ($c = mysqli_fetch_array($characters)) { ?>
										<option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
										<?php } ?>
									</select>
									<select class="standard-select ajax-table-filter-select" data-filtertype="item">
										<option value="0">ITEM</option>
										<?php while ($i = mysqli_fetch_array($items)) { ?>
										<option value="<?php echo $i['id']; ?>"><?php echo $i['name']; ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="ajax-table-pager">
									<input type="button" class="standard-button ajax-table-btn page-beginning" value="<<" disabled>
									<input type="button" class="standard-button ajax-table-btn page-back" value="<" disabled>
									<span>Page <span class="ajax-table-pager-page">1</span> of <span class="ajax-table-pager-pages"></span></span>
									<input type="button" class="standard-button ajax-table-btn page-forward" value=">" disabled>
									<input type="button" class="standard-button ajax-table-btn page-end" value=">>" disabled>
								</div>
								<table id="loot-log-table" class="ajax-table"	data-src="/src/ajax-tables/loot.php" 
																				data-limit="20" 
																				data-page="1" 
																				data-pages="1" 
																				data-sort="date" 
																				data-order="DESC" 
																				data-filtertype="none" 
																				data-filtervalue="0"
																				data-validfilters="class user character item">
									<thead>
										<tr>
											<th class="ajax-table-header" data-sort="date">Date<span></span></th>
											<th class="ajax-table-header" data-sort="user">User<span></span></th>
											<th class="ajax-table-header" data-sort="character">Character<span></span></th>
											<th class="ajax-table-header" data-sort="item">Item<span></span></th>
											<th class="ajax-table-header" data-sort="type">Type<span></span></th>
											<th>Cost</th>
											<th>Note</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
								<div class="ajax-table-pager">
									<input type="button" class="standard-button ajax-table-btn page-beginning" value="<<" disabled>
									<input type="button" class="standard-button ajax-table-btn page-back" value="<" disabled>
									<span>Page <span class="ajax-table-pager-page">1</span> of <span class="ajax-table-pager-pages"></span></span>
									<input type="button" class="standard-button ajax-table-btn page-forward" value=">" disabled>
									<input type="button" class="standard-button ajax-table-btn page-end" value=">>" disabled>
								</div>
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
		<script src="/src/js/ajaxTables.js"></script>
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
			$('#loot-log-table').on('click', '.edit-loot-btn', function(){
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
			$('#loot-log-table').on('click', '.delete-loot-btn', function(){
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
