<?php
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/shared.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/db_connect.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/stmt_init.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/server_config.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/user.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/verify_user_token.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/items.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/loot_types.php";
	require $_SERVER['DOCUMENT_ROOT'] . "/src/php/locations.php";
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
							<input type="button" class="pre-article-button" id="add-item-btn" value="ADD ITEM"></input>
							<?php } ?>
						</div>
						<div class="right">
							
						</div>
					</div>
					<article>
						<div class="header">
							<h4>Raid Items</h4>
						</div>
						<div class="body">
							<div class="ajax-table-filter">
								<h5>Filter</h5>
								<select class="standard-select ajax-table-filter-select" data-filtertype="location">
									<option value="0">LOCATION</option>
									<?php while ($l = mysqli_fetch_array($locations)) { ?>
									<option value="<?php echo $l['id']; ?>"><?php echo $l['name']; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="ajax-table-results">
								<span><span class="results-count">0</span> results found</span>
							</div>
							<div class="ajax-table-pager">
								<input type="button" class="standard-button ajax-table-btn page-beginning" value="<<" disabled>
								<input type="button" class="standard-button ajax-table-btn page-back" value="<" disabled>
								<span>Page <span class="ajax-table-pager-page">1</span> of <span class="ajax-table-pager-pages"></span></span>
								<input type="button" class="standard-button ajax-table-btn page-forward" value=">" disabled>
								<input type="button" class="standard-button ajax-table-btn page-end" value=">>" disabled>
							</div>
							<table id="raid-items-table" class="ajax-table" 	data-src="/src/ajax-tables/items.php" 
																				data-limit="50" 
																				data-page="1" 
																				data-pages="1" 
																				data-sort="item" 
																				data-order="ASC" 
																				data-filtertype="none" 
																				data-filtervalue="0"
																				data-validfilters="location">
								<thead>
									<tr>
										<th class="ajax-table-header" data-sort="item">Name<span></span></th>
										<th class="ajax-table-header" data-sort="type">Type<span></span></th>
										<th>Priority</th>
										<th>Comment</th>
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
					</article>
				</div>
			</div>
		</div>
		<?php if ($user['security'] >= 2) { ?>
		<div class="full-overlay" id="overlay-add-item">
			<div class="scrolling-table-container">
				<h2>Add Item</h2>
				<form method="POST" id="add-item-form" action="/src/php/add_item.php"></form>
				<table>
					<tr>
						<td><b>Item ID</b>:</td>
						<td>
							<div class="table-cell">
								<input type="number" class="standard-input" id="item-id" form="add-item-form" name="item_id" max="999999" required></input>
								<span>Be sure to use the proper item code ID used in the game (refer to a database website).</span>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Name</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" class="standard-input" id="item-name" form="add-item-form" name="item_name" required></input>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Quality</b>:</td>
						<td>
							<div class="table-cell">
								<select class="standard-select" id="item-quality" form="add-item-form" name="item_quality" required>
									<option value="1">Poor</option>
									<option value="2">Common</option>
									<option value="3">Uncommon</option>
									<option value="4">Rare</option>
									<option value="5">Epic</option>
									<option value="6">Legendary</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Location</b>:</td>
						<td>
							<div class="table-cell">
								<select class="standard-select" id="item-location" form="add-item-form" name="item_location" required>
									<?php 
									mysqli_data_seek($locations, 0);
									while ($lc = mysqli_fetch_array($locations)) {
									echo "<option value='" . $lc['id'] . "'>" . $lc['name'] . "</option>";
									} ?>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Default Type</b>:</td>
						<td>
							<div class="table-cell">
								<select class="standard-select" id="item-item" form="add-item-form" name="item_type" required>
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
						<td><b>Loot Priority</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" class="standard-input" id="item-priority" form="add-item-form" name="item_priority"></input>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Comment</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" class="standard-input" id="item-comment" form="add-item-form" name="item_comment"></input>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">						
							<input type="submit" class="standard-button" form="add-item-form" value="SUBMIT"></input>
							<input type="button" class="standard-button close-overlay-btn" value="CANCEL"></input>
						</td>
					</tr>
				</table>
			</div>
		</div>	
		
		<div class="full-overlay" id="overlay-edit-item">
			<div class="scrolling-table-container">
				<h2>Edit Item</h2>
				<form method="POST" id="edit-item-form" action="/src/php/edit_item.php"></form>
				<table>
					<tr>
						<td><b>Item ID</b>:</td>
						<td>
							<div class="table-cell">
								<input type="number" class="standard-input" id="edit-item-id" form="edit-item-form" name="item_id" readonly></input>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Name</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" class="standard-input" id="edit-item-name" readonly></input>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Quality</b>:</td>
						<td>
							<div class="table-cell">
								<select class="standard-select" id="edit-item-quality" form="edit-item-form" name="item_quality" required>
									<option value="1">Poor</option>
									<option value="2">Common</option>
									<option value="3">Uncommon</option>
									<option value="4">Rare</option>
									<option value="5">Epic</option>
									<option value="6">Legendary</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Location</b>:</td>
						<td>
							<div class="table-cell">
								<select class="standard-select" id="edit-item-location" form="edit-item-form" name="item_location" required>
									<?php 
									mysqli_data_seek($locations, 0);
									while ($lc = mysqli_fetch_array($locations)) {
									echo "<option value='" . $lc['id'] . "'>" . $lc['name'] . "</option>";
									} ?>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Default Type</b>:</td>
						<td>
							<div class="table-cell">
								<select class="standard-select" id="edit-item-type" form="edit-item-form" name="item_type" required>
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
						<td><b>Loot Priority</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" class="standard-input" id="edit-item-priority" form="edit-item-form" name="item_priority"></input>
							</div>
						</td>
					</tr>
					<tr>
						<td><b>Comment</b>:</td>
						<td>
							<div class="table-cell">
								<input type="text" class="standard-input" id="edit-item-comment" form="edit-item-form" name="item_comment"></input>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" class="standard-button" form="edit-item-form" value="SUBMIT"></input>
							<input type="button" class="standard-button close-overlay-btn" value="CANCEL"></input>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<?php } ?>
		<script src="/src/js/ajaxTables.js"></script>
		<script>
			// toggle overlays
			$('#add-item-btn').click(function(){
				$('#overlay-add-item').slideDown(250);
			});
			$('.close-overlay-btn').click(function(){
				$('.full-overlay').slideUp(250);
			});
			
			// open edit overlay
			$('#raid-items-table').on('click', '.edit-item-btn', function(){
				let editID = $(this).data('id');
				let editName = $(this).data('name');
				let editQuality = $(this).data('quality');
				let editLocation = $(this).data('location');
				let editType = $(this).data('type');
				let editPriority = $(this).data('priority');
				let editComment = $(this).data('comment');
				
				$('#edit-item-id').val(editID);
				$('#edit-item-name').val(editName);
				$('#edit-item-quality').val(editQuality);
				$('#edit-item-location').val(editLocation);
				$('#edit-item-type').val(editType);
				$('#edit-item-priority').val(editPriority);
				$('#edit-item-comment').val(editComment);
				
				$('#overlay-edit-item').slideDown(250);
			});
			
			// delete item
			$('#raid-items-table').on('click', '.delete-item-btn', function(){
				if (confirm("Are you sure you want to delete this item?")) {
					
					let itemID = $(this).data('id');
					let par = $(this).parent().parent();;
					let rowData = { 'item_id': itemID };
					$.ajax({
						url: "/src/php/delete_item.php",
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
